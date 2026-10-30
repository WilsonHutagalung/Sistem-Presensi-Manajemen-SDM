<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Permission;
use App\Models\Leave;
use App\Models\AttendanceCorrection;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AttendanceSystemTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_user_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'budisantoso@gmail.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard/employee');
        $this->assertAuthenticated();
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'budisantoso@gmail.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_employee_can_clock_in(): void
    {
        $user = User::where('email', 'budisantoso@gmail.com')->first();
        $employee = $user->employee;

        // Clear today's seeded attendance for clean test
        Attendance::where('employee_id', $employee->id)->whereDate('date', Carbon::today()->toDateString())->delete();

        $response = $this->actingAs($user)->post('/attendance/clock-in');

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('attendances', [
            'employee_id' => $employee->id,
        ]);
    }

    public function test_duplicate_clock_in_is_prevented(): void
    {
        $user = User::where('email', 'budisantoso@gmail.com')->first();
        $employee = $user->employee;

        $existing = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', Carbon::today()->toDateString())
            ->first();

        if (!$existing) {
            Attendance::create([
                'employee_id' => $employee->id,
                'date' => Carbon::today()->toDateString(),
                'clock_in' => '07:55:00',
                'status' => 'Present',
            ]);
        }

        $response = $this->actingAs($user)->post('/attendance/clock-in');

        $response->assertSessionHas('error');
    }

    public function test_clock_out_requires_clock_in_first(): void
    {
        $user = User::where('email', 'sitirahmawati@gmail.com')->first();
        $employee = $user->employee;

        Attendance::where('employee_id', $employee->id)->whereDate('date', Carbon::today()->toDateString())->delete();

        $response = $this->actingAs($user)->post('/attendance/clock-out');

        $response->assertSessionHas('error');
    }

    public function test_employee_can_clock_out_after_clock_in(): void
    {
        $user = User::where('email', 'budisantoso@gmail.com')->first();
        $employee = $user->employee;

        $existing = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', Carbon::today()->toDateString())
            ->first();

        if (!$existing) {
            $existing = Attendance::create([
                'employee_id' => $employee->id,
                'date' => Carbon::today()->toDateString(),
                'clock_in' => '07:55:00',
                'clock_out' => null,
                'status' => 'Present',
            ]);
        } else {
            $existing->update(['clock_out' => null]);
        }

        $response = $this->actingAs($user)->post('/attendance/clock-out');

        $response->assertSessionHas('success');
        $attendance = Attendance::where('employee_id', $employee->id)->whereDate('date', Carbon::today()->toDateString())->first();
        $this->assertNotNull($attendance->clock_out);
    }

    public function test_permission_approval_updates_attendance_status(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $employee = User::where('email', 'budisantoso@gmail.com')->first()->employee;
        $targetDate = Carbon::tomorrow()->toDateString();

        $permission = Permission::create([
            'employee_id' => $employee->id,
            'date' => $targetDate,
            'reason' => 'Urusan keluarga',
            'status' => 'Pending',
        ]);

        $response = $this->actingAs($admin)->post("/permissions/{$permission->id}/approve");

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('permissions', [
            'id' => $permission->id,
            'status' => 'Approved',
        ]);
        $this->assertDatabaseHas('attendances', [
            'employee_id' => $employee->id,
            'status' => 'Permission',
        ]);
    }

    public function test_employee_cannot_access_admin_management_routes(): void
    {
        $employee = User::where('email', 'budisantoso@gmail.com')->first();

        $response = $this->actingAs($employee)->get('/employees');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_employee_management_routes(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();

        $response = $this->actingAs($admin)->get('/employees');

        $response->assertStatus(200);
    }

    public function test_non_super_admin_cannot_promote_employee_to_super_admin(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $employee = $admin->employee;

        $response = $this->actingAs($admin)->put("/employees/{$employee->id}", [
            'employee_number' => $employee->employee_number,
            'name' => $employee->name,
            'email' => $admin->email,
            'password' => '',
            'role' => 'super_admin',
            'gender' => $employee->gender,
            'department_id' => $employee->department_id,
            'position_id' => $employee->position_id,
            'join_date' => $employee->join_date->toDateString(),
            'status' => 'active',
        ]);

        $response->assertForbidden();
        $this->assertNotEquals('super_admin', $admin->fresh()->role);
    }

    public function test_manager_cannot_approve_leave_outside_department(): void
    {
        $manager = User::where('email', 'manager@example.com')->first();
        $financeEmployee = User::where('email', 'sitirahmawati@gmail.com')->first()->employee;
        $leave = Leave::create([
            'employee_id' => $financeEmployee->id,
            'start_date' => Carbon::tomorrow()->toDateString(),
            'end_date' => Carbon::tomorrow()->toDateString(),
            'reason' => 'Urusan keluarga',
            'status' => 'Pending',
        ]);

        $response = $this->actingAs($manager)->post("/leaves/{$leave->id}/approve");

        $response->assertForbidden();
        $this->assertDatabaseHas('leaves', ['id' => $leave->id, 'status' => 'Pending']);
    }

    public function test_employee_cannot_submit_correction_for_other_employee_attendance(): void
    {
        $employee = User::where('email', 'budisantoso@gmail.com')->first();
        $otherAttendance = Attendance::whereHas('employee.user', function ($query) {
            $query->where('email', 'sitirahmawati@gmail.com');
        })->first();

        $response = $this->actingAs($employee)->post('/corrections', [
            'attendance_id' => $otherAttendance->id,
            'requested_clock_in' => '08:00',
            'reason' => 'Koreksi',
        ]);

        $response->assertSessionHasErrors('attendance_id');
        $this->assertDatabaseMissing('attendance_corrections', [
            'attendance_id' => $otherAttendance->id,
            'employee_id' => $employee->employee->id,
        ]);
    }

    public function test_permission_attachment_is_not_publicly_accessible(): void
    {
        Storage::fake('local');
        $employee = User::where('email', 'budisantoso@gmail.com')->first();
        $permission = Permission::create([
            'employee_id' => $employee->employee->id,
            'date' => Carbon::today()->toDateString(),
            'reason' => 'Lampiran',
            'attachment' => 'attachments/private.pdf',
            'status' => 'Pending',
        ]);
        Storage::disk('local')->put('attachments/private.pdf', 'private');

        $this->actingAs($employee)->get("/permissions/{$permission->id}/attachment")->assertOk();
        $this->get('/storage/attachments/private.pdf')->assertForbidden();
    }

    public function test_manager_report_is_scoped_to_their_department(): void
    {
        $manager = User::where('email', 'manager@example.com')->first();
        $response = $this->actingAs($manager)->get('/reports');

        $response->assertOk();
        $response->assertSee('Budi Santoso');
        $response->assertDontSee('Siti Rahmawati');
    }
}
