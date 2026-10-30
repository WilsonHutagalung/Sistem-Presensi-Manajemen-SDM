<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Support\SecurityAudit;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['user', 'department', 'position']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_number', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $employees = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();
        $departments = Department::all();

        return view('employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        $departments = Department::all();
        $positions = Position::all();
        return view('employees.create', compact('departments', 'positions'));
    }

    public function store(Request $request)
    {
        $allowedRoles = Auth::user()->isSuperAdmin()
            ? ['super_admin', 'admin', 'hr_admin', 'manager', 'employee']
            : ['manager', 'employee'];

        $request->validate([
            'employee_number' => ['required', 'string', 'unique:employees,employee_number'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in($allowedRoles)],
            'phone' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'gender' => ['required', Rule::in(['L', 'P'])],
            'birth_date' => ['nullable', 'date'],
            'department_id' => ['required', 'exists:departments,id'],
            'position_id' => ['required', 'exists:positions,id'],
            'join_date' => ['required', 'date'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        Employee::create([
            'user_id' => $user->id,
            'employee_number' => $request->employee_number,
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'department_id' => $request->department_id,
            'position_id' => $request->position_id,
            'join_date' => $request->join_date,
            'status' => $request->status,
        ]);

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    public function edit(Employee $employee)
    {
        $departments = Department::all();
        $positions = Position::all();
        return view('employees.edit', compact('employee', 'departments', 'positions'));
    }

    public function update(Request $request, Employee $employee)
    {
        if (!Auth::user()->isSuperAdmin() && !in_array($employee->user?->role, ['manager', 'employee'], true)) {
            abort(403);
        }

        $allowedRoles = Auth::user()->isSuperAdmin()
            ? ['super_admin', 'admin', 'hr_admin', 'manager', 'employee']
            : ['manager', 'employee'];

        $request->validate([
            'employee_number' => ['required', 'string', Rule::unique('employees', 'employee_number')->ignore($employee->id)],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($employee->user_id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', Rule::in($allowedRoles)],
            'phone' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'gender' => ['required', Rule::in(['L', 'P'])],
            'birth_date' => ['nullable', 'date'],
            'department_id' => ['required', 'exists:departments,id'],
            'position_id' => ['required', 'exists:positions,id'],
            'join_date' => ['required', 'date'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $employee->user->update($userData);
        if (array_key_exists('password', $userData) || $employee->user->wasChanged('role')) {
            SecurityAudit::record('employee_credentials_or_role_changed', $request, [
                'employee_id' => $employee->id,
            ]);
        }

        $employee->update([
            'employee_number' => $request->employee_number,
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'department_id' => $request->department_id,
            'position_id' => $request->position_id,
            'join_date' => $request->join_date,
            'status' => $request->status,
        ]);

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function show(Employee $employee)
    {
        $employee->load(['user', 'department', 'position', 'attendances' => function ($q) {
            $q->orderBy('date', 'desc')->limit(10);
        }]);
        return view('employees.show', compact('employee'));
    }
}
