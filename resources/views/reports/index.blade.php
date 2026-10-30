@extends('layouts.app')

@section('title', 'Laporan Presensi Karyawan')
@section('header_title', 'Laporan & Rekap Presensi Karyawan')

@section('content')
<div class="space-y-5 max-w-7xl mx-auto">
    <div>
        <p class="stitch-eyebrow">Laporan & Rekap</p>
        <h2 class="text-2xl font-bold text-[#1B1C19]">Laporan Presensi</h2>
        <p class="text-xs text-[#746D67] mt-1">Gunakan filter untuk meninjau dan mengunduh rekap kehadiran.</p>
    </div>

    <!-- Filter & Bilah Ekspor -->
    <div class="stitch-card p-4 flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('reports.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Departemen</label>
                <select name="department_id" class="px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 outline-none">
                    <option value="">-- Semua --</option>
                    @foreach($departments as $d)
                        <option value="{{ $d->id }}" {{ request('department_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Karyawan</label>
                <select name="employee_id" class="px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 outline-none">
                    <option value="">-- Semua --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Status</label>
                <select name="status" class="px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 outline-none">
                    <option value="">-- Semua --</option>
                    <option value="Present" {{ request('status') == 'Present' ? 'selected' : '' }}>Present</option>
                    <option value="Late" {{ request('status') == 'Late' ? 'selected' : '' }}>Late</option>
                    <option value="Permission" {{ request('status') == 'Permission' ? 'selected' : '' }}>Permission</option>
                    <option value="Leave" {{ request('status') == 'Leave' ? 'selected' : '' }}>Leave</option>
                    <option value="Absent" {{ request('status') == 'Absent' ? 'selected' : '' }}>Absent</option>
                </select>
            </div>

            <div class="pt-5 flex items-center space-x-2">
                <button type="submit" class="stitch-button px-4 py-2 text-xs">
                    Filter Laporan
                </button>
            </div>
        </form>

        <a href="{{ route('reports.export', request()->all()) }}" class="stitch-button px-5 py-2.5 text-xs flex items-center whitespace-nowrap self-end md:self-auto">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export Excel / CSV
        </a>
    </div>

    <!-- Tabel -->
    <div class="stitch-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-[#4D4540]">
                <thead class="bg-[#F5F3EE] text-[10px] font-bold uppercase tracking-wider text-[#746D67] border-b border-[#E8E3DC]">
                    <tr>
                        <th class="px-6 py-3.5">Employee ID</th>
                        <th class="px-6 py-3.5">Nama Karyawan</th>
                        <th class="px-6 py-3.5">Departemen</th>
                        <th class="px-6 py-3.5">Tanggal</th>
                        <th class="px-6 py-3.5">Clock In</th>
                        <th class="px-6 py-3.5">Clock Out</th>
                        <th class="px-6 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($attendances as $att)
                        <tr class="hover:bg-[#FFF8EF] transition border-b border-[#F0EEE9]">
                            <td class="px-6 py-4 font-mono font-semibold text-slate-700">
                                {{ $att->employee->employee_number ?? '-' }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $att->employee->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-700">
                                {{ $att->employee->department->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-700">
                                {{ $att->date ? $att->date->format('Y-m-d') : '-' }}
                            </td>
                            <td class="px-6 py-4 font-mono font-semibold text-slate-700">
                                {{ $att->clock_in ?? '--:--' }}
                            </td>
                            <td class="px-6 py-4 font-mono font-semibold text-slate-700">
                                {{ $att->clock_out ?? '--:--' }}
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
                            <td colspan="7" class="px-6 py-8 text-center text-slate-400">Tidak ada data laporan.</td>
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
