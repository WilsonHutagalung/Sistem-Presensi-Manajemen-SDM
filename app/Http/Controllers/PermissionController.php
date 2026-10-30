<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Permission;
use App\Models\Attendance;
use Carbon\Carbon;

class PermissionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Permission::with(['employee.department', 'approver']);

        if ($user->isEmployee()) {
            $query->where('employee_id', $user->employee->id);
        } elseif ($user->isManager() && $user->employee) {
            $query->whereHas('employee', function ($q) use ($user) {
                $q->where('department_id', $user->employee->department_id);
            });
        }

        $permissions = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => ['required', 'date'],
            'reason' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        $employee = Auth::user()->employee;
        if (!$employee) {
            return back()->with('error', 'Data karyawan tidak ditemukan.');
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'local');
        }

        Permission::create([
            'employee_id' => $employee->id,
            'date' => $request->date,
            'reason' => $request->reason,
            'attachment' => $attachmentPath,
            'status' => 'Pending',
        ]);

        return redirect()->route('permissions.index')->with('success', 'Pengajuan izin berhasil dikirim dan menunggu persetujuan.');
    }

    public function approve(Permission $permission)
    {
        $user = Auth::user();
        $this->authorizeApproval($permission);
        abort_if($permission->status !== 'Pending', 422, 'Pengajuan ini sudah diproses.');

        $permission->update([
            'status' => 'Approved',
            'approved_by' => $user->id,
            'approved_at' => Carbon::now(),
        ]);

        // BR-08: Izin yang disetujui memperbarui status kehadiran menjadi Izin (Permission)
        $dateStr = Carbon::parse($permission->date)->format('Y-m-d');
        $att = Attendance::where('employee_id', $permission->employee_id)
            ->whereDate('date', $dateStr)
            ->first();

        if ($att) {
            $att->update(['status' => 'Permission']);
        } else {
            Attendance::create([
                'employee_id' => $permission->employee_id,
                'date' => $dateStr,
                'status' => 'Permission',
            ]);
        }

        return back()->with('success', 'Pengajuan izin berhasil disetujui.');
    }

    public function reject(Permission $permission)
    {
        $user = Auth::user();
        $this->authorizeApproval($permission);
        abort_if($permission->status !== 'Pending', 422, 'Pengajuan ini sudah diproses.');

        $permission->update([
            'status' => 'Rejected',
            'approved_by' => $user->id,
            'approved_at' => Carbon::now(),
        ]);

        return back()->with('success', 'Pengajuan izin ditolak.');
    }

    public function attachment(Permission $permission)
    {
        $user = Auth::user();
        $employee = $permission->employee;
        abort_unless($employee, 404);

        $canView = $user->isAdmin()
            || ($user->employee && (
                $employee->id === $user->employee->id
                || ($user->isManager() && $employee->department_id === $user->employee->department_id)
            ));
        abort_unless($canView, 403);
        abort_unless($permission->attachment && Storage::disk('local')->exists($permission->attachment), 404);

        return Storage::disk('local')->download($permission->attachment);
    }

    private function authorizeApproval(Permission $permission): void
    {
        $user = Auth::user();
        $employee = $permission->employee;
        $allowed = $user->isAdmin()
            || ($user->isManager() && $user->employee && $employee
                && $employee->department_id === $user->employee->department_id);
        abort_unless($allowed, 403);
    }
}
