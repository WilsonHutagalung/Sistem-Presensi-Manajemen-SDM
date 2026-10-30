<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use App\Models\Employee;
use App\Models\Shift;
use App\Models\EmployeeShift;
use App\Models\WorkSchedule;
use App\Models\Attendance;
use App\Models\Permission;
use App\Models\Leave;
use App\Models\AttendanceCorrection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Departments - Operasional ekspor gula aren
        $deptProd = Department::create(['name' => 'Produksi & Pengolahan', 'description' => 'Penyadapan, pemasakan, pengeringan, dan pengemasan gula aren']);
        $deptHR = Department::create(['name' => 'SDM & Umum', 'description' => 'Pengelolaan sumber daya manusia, fasilitas, dan kepatuhan operasional']);
        $deptFin = Department::create(['name' => 'Keuangan & Ekspor', 'description' => 'Keuangan, dokumen ekspor, bea cukai, dan administrasi perdagangan']);
        $deptQC = Department::create(['name' => 'Quality Control', 'description' => 'Jaminan mutu, keamanan pangan, traceability, dan standar ekspor']);
        $deptLog = Department::create(['name' => 'Gudang & Logistik', 'description' => 'Pengelolaan bahan baku, persediaan, pengiriman, dan rantai pasok']);
        $deptIT = Department::create(['name' => 'Teknologi Informasi', 'description' => 'Pengelolaan sistem informasi, jaringan, perangkat, dan dukungan teknologi']);

        // 2. Positions
        $posProd = Position::create(['name' => 'Operator Pengolahan Gula Aren', 'description' => 'Menjalankan proses produksi dan pengemasan gula aren']);
        $posHR = Position::create(['name' => 'Staf SDM & Umum', 'description' => 'Administrasi SDM dan kebutuhan operasional kantor']);
        $posExport = Position::create(['name' => 'Staf Administrasi Ekspor', 'description' => 'Menyiapkan dokumen ekspor dan administrasi pengiriman internasional']);
        $posMgr = Position::create(['name' => 'Manajer Operasional', 'description' => 'Mengelola target produksi, mutu, dan ketepatan pengiriman']);
        $posQC = Position::create(['name' => 'Staf Quality Control', 'description' => 'Memeriksa mutu produk dan kepatuhan standar ekspor']);
        $posWarehouse = Position::create(['name' => 'Koordinator Gudang', 'description' => 'Mengelola stok bahan baku, produk jadi, dan distribusi']);
        $posIT = Position::create(['name' => 'IT Staff', 'description' => 'Mengelola dukungan teknis, jaringan, perangkat, dan sistem informasi']);

        // 3. Shifts
        $shiftPagi = Shift::create(['name' => 'Shift Pagi', 'start_time' => '08:00:00', 'end_time' => '17:00:00', 'late_tolerance' => 15, 'status' => 'active']);
        $shiftSiang = Shift::create(['name' => 'Shift Siang', 'start_time' => '13:00:00', 'end_time' => '21:00:00', 'late_tolerance' => 15, 'status' => 'active']);
        $shiftMalam = Shift::create(['name' => 'Shift Malam', 'start_time' => '21:00:00', 'end_time' => '05:00:00', 'late_tolerance' => 15, 'status' => 'active']);

        // 4. Work Schedule (Monday - Sunday)
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        foreach ($days as $day) {
            WorkSchedule::create([
                'day_of_week' => $day,
                'is_working_day' => !in_array($day, ['Saturday', 'Sunday']),
            ]);
        }

        // 5. Users & Employees
        // Super Admin
        $uSuper = User::create([
            'name' => 'Raka Pratama',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);
        $empSuper = Employee::create([
            'user_id' => $uSuper->id,
            'employee_number' => 'AGR-001',
            'name' => 'Raka Pratama',
            'phone' => '081234567890',
            'address' => 'Jakarta, Indonesia',
            'gender' => 'L',
            'birth_date' => '1990-01-01',
            'department_id' => $deptProd->id,
            'position_id' => $posMgr->id,
            'join_date' => '2020-01-01',
            'status' => 'active',
        ]);

        // Admin / HR
        $uAdmin = User::create([
            'name' => 'Dewi Lestari',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        $empAdmin = Employee::create([
            'user_id' => $uAdmin->id,
            'employee_number' => 'AGR-002',
            'name' => 'Dewi Lestari',
            'phone' => '081234567891',
            'address' => 'Bandung, Indonesia',
            'gender' => 'P',
            'birth_date' => '1992-05-15',
            'department_id' => $deptHR->id,
            'position_id' => $posHR->id,
            'join_date' => '2021-03-01',
            'status' => 'active',
        ]);

        // Manager
        $uManager = User::create([
            'name' => 'Andi Saputra',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);
        $empManager = Employee::create([
            'user_id' => $uManager->id,
            'employee_number' => 'AGR-003',
            'name' => 'Andi Saputra',
            'phone' => '081234567892',
            'address' => 'Surabaya, Indonesia',
            'gender' => 'L',
            'birth_date' => '1988-08-20',
            'department_id' => $deptProd->id,
            'position_id' => $posMgr->id,
            'join_date' => '2019-06-15',
            'status' => 'active',
        ]);

        // Operator produksi
        $uEmployee = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budisantoso@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);
        $empBudi = Employee::create([
            'user_id' => $uEmployee->id,
            'employee_number' => 'AGR-004',
            'name' => 'Budi Santoso',
            'phone' => '081234567893',
            'address' => 'Yogyakarta, Indonesia',
            'gender' => 'L',
            'birth_date' => '1995-12-10',
            'department_id' => $deptProd->id,
            'position_id' => $posProd->id,
            'join_date' => '2022-01-10',
            'status' => 'active',
        ]);

        // Staf quality control
        $uSiti = User::create([
            'name' => 'Siti Rahmawati',
            'email' => 'sitirahmawati@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);
        $empSiti = Employee::create([
            'user_id' => $uSiti->id,
            'employee_number' => 'AGR-005',
            'name' => 'Siti Rahmawati',
            'phone' => '081234567894',
            'address' => 'Semarang, Indonesia',
            'gender' => 'P',
            'birth_date' => '1996-03-25',
            'department_id' => $deptQC->id,
            'position_id' => $posQC->id,
            'join_date' => '2022-04-01',
            'status' => 'active',
        ]);

        // Staf teknologi informasi
        $uWilson = User::create([
            'name' => 'Wilson',
            'email' => 'wilson@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);
        $empWilson = Employee::create([
            'user_id' => $uWilson->id,
            'employee_number' => 'AGR-006',
            'name' => 'Wilson',
            'phone' => '081234567895',
            'address' => 'Jakarta, Indonesia',
            'gender' => 'L',
            'birth_date' => '1994-07-12',
            'department_id' => $deptIT->id,
            'position_id' => $posIT->id,
            'join_date' => '2023-02-01',
            'status' => 'active',
        ]);

        // 6. Assign Shifts
        foreach ([$empSuper, $empAdmin, $empManager, $empBudi, $empSiti, $empWilson] as $emp) {
            EmployeeShift::create([
                'employee_id' => $emp->id,
                'shift_id' => $shiftPagi->id,
                'effective_date' => Carbon::now()->startOfMonth()->toDateString(),
            ]);
        }

        // 7. Historical attendance data for current month
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();

        for ($d = $startOfMonth->copy(); $d->lte($today); $d->addDay()) {
            $dayName = $d->format('l');
            if (in_array($dayName, ['Saturday', 'Sunday'])) {
                continue; // Non-working day
            }

            if ($d->isToday()) {
                // Presensi operator produksi (sudah clock in)
                Attendance::create([
                    'employee_id' => $empBudi->id,
                    'date' => $d->toDateString(),
                    'clock_in' => '07:55:00',
                    'clock_out' => null,
                    'status' => 'Present',
                ]);
                // Presensi quality control (terlambat)
                Attendance::create([
                    'employee_id' => $empSiti->id,
                    'date' => $d->toDateString(),
                    'clock_in' => '08:25:00',
                    'clock_out' => null,
                    'status' => 'Late',
                ]);
                // Admin SDM hadir
                Attendance::create([
                    'employee_id' => $empAdmin->id,
                    'date' => $d->toDateString(),
                    'clock_in' => '07:50:00',
                    'clock_out' => null,
                    'status' => 'Present',
                ]);
                Attendance::create([
                    'employee_id' => $empWilson->id,
                    'date' => $d->toDateString(),
                    'clock_in' => '07:55:00',
                    'clock_out' => null,
                    'status' => 'Present',
                ]);
            } else {
                // Past days attendance
                Attendance::create([
                    'employee_id' => $empBudi->id,
                    'date' => $d->toDateString(),
                    'clock_in' => '07:58:00',
                    'clock_out' => '17:05:00',
                    'status' => 'Present',
                ]);

                // Randomly present or late for Siti
                $status = ($d->day % 3 == 0) ? 'Late' : 'Present';
                $clockInTime = ($status === 'Late') ? '08:20:00' : '07:50:00';
                Attendance::create([
                    'employee_id' => $empSiti->id,
                    'date' => $d->toDateString(),
                    'clock_in' => $clockInTime,
                    'clock_out' => '17:10:00',
                    'status' => $status,
                ]);

                Attendance::create([
                    'employee_id' => $empAdmin->id,
                    'date' => $d->toDateString(),
                    'clock_in' => '07:45:00',
                    'clock_out' => '17:00:00',
                    'status' => 'Present',
                ]);
                Attendance::create([
                    'employee_id' => $empWilson->id,
                    'date' => $d->toDateString(),
                    'clock_in' => '07:55:00',
                    'clock_out' => '17:05:00',
                    'status' => 'Present',
                ]);
            }
        }

        // 8. Contoh pengajuan izin operator produksi
        Permission::create([
            'employee_id' => $empBudi->id,
            'date' => $today->copy()->addDays(2)->toDateString(),
            'reason' => 'Pemeriksaan kesehatan berkala pekerja produksi',
            'status' => 'Pending',
        ]);

        // 9. Contoh pengajuan cuti staf quality control
        Leave::create([
            'employee_id' => $empSiti->id,
            'start_date' => $today->copy()->addDays(5)->toDateString(),
            'end_date' => $today->copy()->addDays(7)->toDateString(),
            'reason' => 'Cuti tahunan untuk keperluan keluarga',
            'status' => 'Pending',
        ]);
    }
}
