<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::withCount('employees')->get();
        return view('positions.index', compact('positions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:positions,name'],
            'description' => ['nullable', 'string'],
        ]);

        Position::create($request->only('name', 'description'));

        return back()->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:positions,name,' . $position->id],
            'description' => ['nullable', 'string'],
        ]);

        $position->update($request->only('name', 'description'));

        return back()->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Position $position)
    {
        if ($position->employees()->count() > 0) {
            return back()->with('error', 'Jabatan tidak dapat dihapus karena sedang digunakan oleh karyawan.');
        }

        $position->delete();
        return back()->with('success', 'Jabatan berhasil dihapus.');
    }
}
