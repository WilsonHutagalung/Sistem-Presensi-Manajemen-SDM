<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Permission;
use App\Models\Leave;
use App\Models\WorkSchedule;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        $today = Carbon::today()->toDateString();
        $user = Auth::user();

        // Cakupan data untuk manajer (hanya departemen bersangkutan) vs admin/super_admin (semua)
        $employeeQuery = Employee::query();
        if ($user->role === 'manager' && $user->employee) {
            $employeeQuery->where('department_id', $user->employee->department_id);
        }

        $totalEmployees = $employeeQuery->where('status', 'active')->count();
        $employeeIds = $employeeQuery->pluck('id');

        $attendancesToday = Attendance::whereIn('employee_id', $employeeIds)
            ->where('date', $today)
            ->get();

        $totalHadir = $attendancesToday->where('status', 'Present')->count();
        $totalTerlambat = $attendancesToday->where('status', 'Late')->count();
        $totalIzin = $attendancesToday->where('status', 'Permission')->count();
        $totalCuti = $attendancesToday->where('status', 'Leave')->count();

        // Perhitungan ketidakhadiran
        $attendedCount = $attendancesToday->count();
        $totalTidakHadir = max(0, $totalEmployees - $attendedCount);

        $recentAttendances = Attendance::with(['employee.department', 'employee.position'])
            ->whereIn('employee_id', $employeeIds)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.admin', compact(
            'totalEmployees',
            'totalHadir',
            'totalTerlambat',
            'totalIzin',
            'totalCuti',
            'totalTidakHadir',
            'recentAttendances'
        ));
    }

    public function employeeDashboard()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('login')->withErrors(['email' => 'Data karyawan tidak ditemukan.']);
        }

        $today = Carbon::today()->toDateString();
        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->where('date', $today)
            ->first();

        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        $monthlyAttendances = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();

        $totalHadirBulanIni = $monthlyAttendances->whereIn('status', ['Present', 'Late'])->count();
        $totalTerlambatBulanIni = $monthlyAttendances->where('status', 'Late')->count();
        $totalIzinBulanIni = $monthlyAttendances->where('status', 'Permission')->count();
        $totalCutiBulanIni = $monthlyAttendances->where('status', 'Leave')->count();

        $shift = $employee->currentShift();

        return view('dashboard.employee', compact(
            'employee',
            'todayAttendance',
            'shift',
            'totalHadirBulanIni',
            'totalTerlambatBulanIni',
            'totalIzinBulanIni',
            'totalCutiBulanIni'
        ));
    }
}
