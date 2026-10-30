<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Department;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function clockIn(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee || $employee->status !== 'active') {
            return back()->with('error', 'Status akun Anda tidak aktif.');
        }

        $now = Carbon::now();
        $today = $now->toDateString();
        $currentTime = $now->format('H:i:s');

        // BR-01: Duplikasi Clock In
        $existing = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        if ($existing && $existing->clock_in) {
            return back()->with('error', 'Anda sudah melakukan Clock In hari ini.');
        }

        $shift = $employee->currentShift();
        $startTime = Carbon::createFromTimeString($shift->start_time);
        $lateLimit = $startTime->copy()->addMinutes($shift->late_tolerance);
        
        $status = $now->greaterThan($lateLimit) ? 'Late' : 'Present';

        if ($existing) {
            $existing->update([
                'clock_in' => $currentTime,
                'status' => $status,
            ]);
        } else {
            Attendance::create([
                'employee_id' => $employee->id,
                'date' => $today,
                'clock_in' => $currentTime,
                'status' => $status,
            ]);
        }

        return back()->with('success', "Berhasil Clock In pada jam {$currentTime}. Status: {$status}.");
    }

    public function clockOut(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee || $employee->status !== 'active') {
            return back()->with('error', 'Status akun Anda tidak aktif.');
        }

        $now = Carbon::now();
        $today = $now->toDateString();
        $currentTime = $now->format('H:i:s');

        // BR-03: Ketergantungan Clock Out (Harus Clock In Terlebih Dahulu)
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance || !$attendance->clock_in) {
            return back()->with('error', 'Anda belum melakukan Clock In hari ini.');
        }

        // BR-02: Duplikasi Clock Out
        if ($attendance->clock_out) {
            return back()->with('error', 'Anda sudah melakukan Clock Out hari ini.');
        }

        $attendance->update([
            'clock_out' => $currentTime,
        ]);

        return back()->with('success', "Berhasil Clock Out pada jam {$currentTime}.");
    }

    public function history(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        $query = Attendance::where('employee_id', $employee->id);

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('month')) {
            $month = Carbon::parse($request->month);
            $query->whereYear('date', $month->year)->whereMonth('date', $month->month);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(15)->withQueryString();

        return view('attendance.history', compact('attendances'));
    }

    public function monitoring(Request $request)
    {
        $user = Auth::user();
        $query = Attendance::with(['employee.department', 'employee.position']);

        if ($user->role === 'manager' && $user->employee) {
            $query->whereHas('employee', function ($q) use ($user) {
                $q->where('department_id', $user->employee->department_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('date', 'desc')->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $departments = Department::all();

        return view('attendance.monitoring', compact('attendances', 'departments'));
    }
}
