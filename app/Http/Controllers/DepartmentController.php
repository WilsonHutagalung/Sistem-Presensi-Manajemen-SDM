<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('employees')->get();
        return view('departments.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments,name'],
            'description' => ['nullable', 'string'],
        ]);

        Department::create($request->only('name', 'description'));

        return back()->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments,name,' . $department->id],
            'description' => ['nullable', 'string'],
        ]);

        $department->update($request->only('name', 'description'));

        return back()->with('success', 'Departemen berhasil diperbarui.');
    }

    public function destroy(Department $department)
    {
        if ($department->employees()->count() > 0) {
            return back()->with('error', 'Departemen tidak dapat dihapus karena masih memiliki karyawan terkait.');
        }

        $department->delete();
        return back()->with('success', 'Departemen berhasil dihapus.');
    }
}
