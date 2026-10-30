<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Leave;
use App\Models\Attendance;
use App\Models\WorkSchedule;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Leave::with(['employee.department', 'approver']);

        if ($user->isEmployee()) {
            $query->where('employee_id', $user->employee->id);
        } elseif ($user->isManager() && $user->employee) {
            $query->whereHas('employee', function ($q) use ($user) {
                $q->where('department_id', $user->employee->department_id);
            });
        }

        $leaves = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('leaves.index', compact('leaves'));
    }

    public function create()
    {
        return view('leaves.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string'],
        ]);

        $employee = Auth::user()->employee;
        if (!$employee) {
            return back()->with('error', 'Data karyawan tidak ditemukan.');
        }

        Leave::create([
            'employee_id' => $employee->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'Pending',
        ]);

        return redirect()->route('leaves.index')->with('success', 'Pengajuan cuti berhasil dikirim dan menunggu persetujuan.');
    }

    public function approve(Leave $leave)
    {
        $user = Auth::user();
        $this->authorizeApproval($leave);
        abort_if($leave->status !== 'Pending', 422, 'Pengajuan ini sudah diproses.');

        $leave->update([
            'status' => 'Approved',
            'approved_by' => $user->id,
            'approved_at' => Carbon::now(),
        ]);

        // BR-07: Cuti yang disetujui memperbarui status kehadiran untuk rentang tanggal pada hari kerja
        $start = Carbon::parse($leave->start_date);
        $end = Carbon::parse($leave->end_date);
        $workingDays = WorkSchedule::where('is_working_day', true)->pluck('day_of_week')->toArray();

        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            if (in_array($d->format('l'), $workingDays)) {
                $dStr = $d->format('Y-m-d');
                $att = Attendance::where('employee_id', $leave->employee_id)
                    ->whereDate('date', $dStr)
                    ->first();

                if ($att) {
                    $att->update(['status' => 'Leave']);
                } else {
                    Attendance::create([
                        'employee_id' => $leave->employee_id,
                        'date' => $dStr,
                        'status' => 'Leave',
                    ]);
                }
            }
        }

        return back()->with('success', 'Pengajuan cuti berhasil disetujui.');
    }

    public function reject(Leave $leave)
    {
        $user = Auth::user();
        $this->authorizeApproval($leave);
        abort_if($leave->status !== 'Pending', 422, 'Pengajuan ini sudah diproses.');

        $leave->update([
            'status' => 'Rejected',
            'approved_by' => $user->id,
            'approved_at' => Carbon::now(),
        ]);

        return back()->with('success', 'Pengajuan cuti ditolak.');
    }

    private function authorizeApproval(Leave $leave): void
    {
        $user = Auth::user();
        $employee = $leave->employee;
        $allowed = $user->isAdmin()
            || ($user->isManager() && $user->employee && $employee
                && $employee->department_id === $user->employee->department_id);
        abort_unless($allowed, 403);
    }
}
