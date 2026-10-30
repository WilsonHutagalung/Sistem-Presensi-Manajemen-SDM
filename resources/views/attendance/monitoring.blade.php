@extends('layouts.app')

@section('title', 'Monitoring Presensi Karyawan')
@section('header_title', 'Monitoring Presensi Perusahaan')

@section('content')
<div class="space-y-5 max-w-7xl mx-auto">
    <div>
        <p class="stitch-eyebrow">Laporan & Tim</p>
        <h2 class="text-2xl font-bold text-[#1B1C19]">Monitoring Presensi</h2>
        <p class="text-xs text-[#746D67] mt-1">Pantau aktivitas kehadiran tim secara terpusat.</p>
    </div>

    <!-- Bilah Filter -->
    <div class="stitch-card p-4">
        <form action="{{ route('attendance.monitoring') }}" method="GET" class="flex flex-wrap items-end gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / NIK Karyawan..." 
                class="px-4 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 outline-none w-full sm:w-64">

            <select name="department_id" class="px-4 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 outline-none">
                <option value="">-- Semua Departemen --</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                @endforeach
            </select>

            <input type="date" name="date" value="{{ request('date') }}" class="px-4 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 outline-none">

            <select name="status" class="px-4 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 outline-none">
                <option value="">-- Semua Status --</option>
                <option value="Present" {{ request('status') == 'Present' ? 'selected' : '' }}>Present</option>
                <option value="Late" {{ request('status') == 'Late' ? 'selected' : '' }}>Late</option>
                <option value="Permission" {{ request('status') == 'Permission' ? 'selected' : '' }}>Permission</option>
                <option value="Leave" {{ request('status') == 'Leave' ? 'selected' : '' }}>Leave</option>
                <option value="Absent" {{ request('status') == 'Absent' ? 'selected' : '' }}>Absent</option>
            </select>

            <button type="submit" class="stitch-button px-5 py-2 text-xs">
                Filter Data
            </button>
            <a href="{{ route('attendance.monitoring') }}" class="px-4 py-2 border border-slate-300 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-50">Reset</a>
        </form>
    </div>

    <!-- Tabel -->
    <div class="stitch-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-[#4D4540]">
                <thead class="bg-[#F5F3EE] text-[10px] font-bold uppercase tracking-wider text-[#746D67] border-b border-[#E8E3DC]">
                    <tr>
                        <th class="px-6 py-3.5">Karyawan</th>
                        <th class="px-6 py-3.5">Departemen & Jabatan</th>
                        <th class="px-6 py-3.5">Tanggal</th>
                        <th class="px-6 py-3.5">Clock In</th>
                        <th class="px-6 py-3.5">Clock Out</th>
                        <th class="px-6 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($attendances as $att)
                        <tr class="hover:bg-[#FFF8EF] transition border-b border-[#F0EEE9]">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $att->employee->name ?? '-' }}</div>
                                <div class="text-xs font-mono text-slate-400">{{ $att->employee->employee_number ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-700">{{ $att->employee->department->name ?? '-' }}</div>
                                <div class="text-xs text-slate-400">{{ $att->employee->position->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-700">
                                {{ $att->date ? $att->date->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 font-mono font-semibold text-slate-700">
                                {{ $att->clock_in ?? '--:--:--' }}
                            </td>
                            <td class="px-6 py-4 font-mono font-semibold text-slate-700">
                                {{ $att->clock_out ?? '--:--:--' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($att->status === 'Present')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">Present</span>
                                @elseif($att->status === 'Late')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">Late</span>
                                @elseif($att->status === 'Permission')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-700">Permission</span>
                                @elseif($att->status === 'Leave')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700">Leave</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700">Absent</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">Tidak ada data presensi yang sesuai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $attendances->links() }}
        </div>
    </div>

</div>
@endsection
