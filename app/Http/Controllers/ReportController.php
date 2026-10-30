<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['employee.department', 'employee.position']);
        $this->scopeToManagerDepartment($query);

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }
        if ($request->filled('department_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(20)->withQueryString();
        $departments = $this->availableDepartments();
        $employees = $this->availableEmployees();

        return view('reports.index', compact('attendances', 'departments', 'employees'));
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $query = Attendance::with(['employee.department', 'employee.position']);
        $this->scopeToManagerDepartment($query);

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }
        if ($request->filled('department_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('date', 'desc')->get();
        $filename = "attendance_report_" . Carbon::now()->format('Y_m_d_His') . ".csv";

        $headers = [
            "Content-Type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=\"{$filename}\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($attendances) {
            $file = fopen('php://output', 'w');
            // Tambahkan UTF-8 BOM untuk kompatibilitas Excel
            fputs($file, "\xEF\xBB\xBF");

            // Baris header
            fputcsv($file, ['Employee ID', 'Employee Name', 'Department', 'Date', 'Clock In', 'Clock Out', 'Status']);

            foreach ($attendances as $att) {
                fputcsv($file, [
                    $this->safeCsvValue($att->employee->employee_number ?? '-'),
                    $this->safeCsvValue($att->employee->name ?? '-'),
                    $this->safeCsvValue($att->employee->department->name ?? '-'),
                    $att->date ? $att->date->format('Y-m-d') : '-',
                    $att->clock_in ?? '-',
                    $att->clock_out ?? '-',
                    $this->safeCsvValue($att->status)
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function scopeToManagerDepartment($query): void
    {
        $user = auth()->user();
        if ($user->isManager() && $user->employee) {
            $query->whereHas('employee', function ($employeeQuery) use ($user) {
                $employeeQuery->where('department_id', $user->employee->department_id);
            });
        }
    }

    private function availableDepartments()
    {
        $user = auth()->user();
        return $user->isManager() && $user->employee
            ? Department::whereKey($user->employee->department_id)->get()
            : Department::all();
    }

    private function availableEmployees()
    {
        $user = auth()->user();
        $query = Employee::where('status', 'active');

        if ($user->isManager() && $user->employee) {
            $query->where('department_id', $user->employee->department_id);
        }

        return $query->get();
    }

    private function safeCsvValue(string $value): string
    {
        return preg_match('/^[=+\-@]/', $value) === 1 ? "'".$value : $value;
    }
}
