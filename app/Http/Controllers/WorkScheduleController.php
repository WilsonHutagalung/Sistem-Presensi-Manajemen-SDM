<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkSchedule;

class WorkScheduleController extends Controller
{
    public function index()
    {
        $schedules = WorkSchedule::all();
        return view('schedules.index', compact('schedules'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'schedules' => ['required', 'array'],
        ]);

        foreach ($request->schedules as $id => $data) {
            WorkSchedule::where('id', $id)->update([
                'is_working_day' => isset($data['is_working_day']),
            ]);
        }

        return back()->with('success', 'Jadwal hari kerja berhasil diperbarui.');
    }
}
