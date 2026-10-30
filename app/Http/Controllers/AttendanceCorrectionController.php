<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\AttendanceCorrection;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceCorrectionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = AttendanceCorrection::with(['employee.department', 'attendance', 'reviewer']);

        if ($user->isEmployee()) {
            $query->where('employee_id', $user->employee->id);
        } elseif ($user->isManager() && $user->employee) {
            $query->whereHas('employee', function ($q) use ($user) {
                $q->where('department_id', $user->employee->department_id);
            });
        }

        $corrections = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('corrections.index', compact('corrections'));
    }

    public function create()
    {
        $employee = Auth::user()->employee;
        $attendances = Attendance::where('employee_id', $employee->id)
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();
        return view('corrections.create', compact('attendances'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'attendance_id' => [
                'required',
                Rule::exists('attendances', 'id')->where(
                    fn ($query) => $query->where('employee_id', Auth::user()->employee?->id)
                ),
            ],
            'requested_clock_in' => ['nullable'],
            'requested_clock_out' => ['nullable'],
            'reason' => ['required', 'string'],
        ]);

        $employee = Auth::user()->employee;
        abort_unless($employee, 403);

        AttendanceCorrection::create([
            'employee_id' => $employee->id,
            'attendance_id' => $request->attendance_id,
            'requested_clock_in' => $request->requested_clock_in,
            'requested_clock_out' => $request->requested_clock_out,
            'reason' => $request->reason,
            'status' => 'Pending',
        ]);

        return redirect()->route('corrections.index')->with('success', 'Pengajuan koreksi absensi berhasil dikirim.');
    }

    public function approve(AttendanceCorrection $correction)
    {
        $user = Auth::user();
        $this->authorizeApproval($correction);
        abort_if($correction->status !== 'Pending', 422, 'Pengajuan ini sudah diproses.');
        abort_unless($correction->attendance
            && $correction->attendance->employee_id === $correction->employee_id, 422, 'Data absensi tidak sesuai.');

        $correction->update([
            'status' => 'Approved',
            'reviewed_by' => $user->id,
            'reviewed_at' => Carbon::now(),
        ]);

        $attendance = $correction->attendance;
        if ($attendance) {
            $dataToUpdate = [];
            if ($correction->requested_clock_in) {
                $dataToUpdate['clock_in'] = $correction->requested_clock_in;
            }
            if ($correction->requested_clock_out) {
                $dataToUpdate['clock_out'] = $correction->requested_clock_out;
            }
            $attendance->update($dataToUpdate);
        }

        return back()->with('success', 'Koreksi absensi disetujui dan data absensi telah diperbarui.');
    }

    public function reject(AttendanceCorrection $correction)
    {
        $user = Auth::user();
        $this->authorizeApproval($correction);
        abort_if($correction->status !== 'Pending', 422, 'Pengajuan ini sudah diproses.');

        $correction->update([
            'status' => 'Rejected',
            'reviewed_by' => $user->id,
            'reviewed_at' => Carbon::now(),
        ]);

        return back()->with('success', 'Pengajuan koreksi absensi ditolak.');
    }

    private function authorizeApproval(AttendanceCorrection $correction): void
    {
        $user = Auth::user();
        $employee = $correction->employee;
        $allowed = $user->isAdmin()
            || ($user->isManager() && $user->employee && $employee
                && $employee->department_id === $user->employee->department_id);
        abort_unless($allowed, 403);
    }
}
