<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;
use App\Models\Employee;
use App\Models\EmployeeShift;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::all();
        $employees = Employee::where('status', 'active')->get();
        return view('shifts.index', compact('shifts', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'late_tolerance' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        Shift::create($request->all());

        return back()->with('success', 'Shift kerja berhasil ditambahkan.');
    }

    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'late_tolerance' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $shift->update($request->all());

        return back()->with('success', 'Shift kerja berhasil diperbarui.');
    }

    public function assign(Request $request)
    {
        $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'shift_id' => ['required', 'exists:shifts,id'],
            'effective_date' => ['required', 'date'],
        ]);

        EmployeeShift::create($request->only('employee_id', 'shift_id', 'effective_date'));

        return back()->with('success', 'Shift berhasil ditugaskan ke karyawan.');
    }
}
