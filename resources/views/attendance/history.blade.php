@extends('layouts.app')

@section('title', 'Riwayat Presensi Saya')
@section('header_title', 'Riwayat Presensi Pribadi')

@section('content')
<div class="space-y-5 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-[.08em] text-[#904D00]">Presensi Saya</p>
            <h2 class="text-2xl font-bold text-[#1B1C19] tracking-tight">Riwayat Presensi Pribadi</h2>
            <p class="text-xs text-[#746D67] mt-1">Pantau dan tinjau rekam kehadiran harian Anda.</p>
        </div>
        <span class="text-xs text-[#746D67]">Terakhir diperbarui {{ now()->format('d M Y, H:i') }} WIB</span>
    </div>

    <!-- Bilah Pencarian & Filter -->
    <div class="stitch-card p-4">
        <form action="{{ route('attendance.history') }}" method="GET" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-[11px] font-semibold text-[#746D67] mb-1">Tanggal spesifik</label>
                <input type="date" name="date" value="{{ request('date') }}" class="px-4 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 outline-none">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-[#746D67] mb-1">Bulan</label>
                <input type="month" name="month" value="{{ request('month') }}" class="px-4 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 outline-none">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-[#746D67] mb-1">Status</label>
                <select name="status" class="px-4 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 outline-none">
                    <option value="">-- Semua Status --</option>
                    <option value="Present" {{ request('status') == 'Present' ? 'selected' : '' }}>Present</option>
                    <option value="Late" {{ request('status') == 'Late' ? 'selected' : '' }}>Late</option>
                    <option value="Permission" {{ request('status') == 'Permission' ? 'selected' : '' }}>Permission</option>
                    <option value="Leave" {{ request('status') == 'Leave' ? 'selected' : '' }}>Leave</option>
                    <option value="Absent" {{ request('status') == 'Absent' ? 'selected' : '' }}>Absent</option>
                </select>
            </div>

            <div class="flex items-center space-x-2">
                <button type="submit" class="stitch-button px-5 py-2 text-xs">Terapkan Filter</button>
                <a href="{{ route('attendance.history') }}" class="px-4 py-2 border border-[#D0C4BD] text-[#746D67] text-xs font-semibold rounded-lg hover:bg-[#F5F3EE]">Reset</a>
            </div>
        </form>
    </div>

    <!-- Tabel Riwayat -->
    <div class="stitch-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-[#4D4540]">
                <thead class="bg-[#F5F3EE] text-[10px] font-bold uppercase tracking-wider text-[#746D67] border-b border-[#E8E3DC]">
                    <tr>
                        <th class="px-6 py-3.5">Tanggal</th>
                        <th class="px-6 py-3.5">Hari</th>
                        <th class="px-6 py-3.5">Clock In</th>
                        <th class="px-6 py-3.5">Clock Out</th>
                        <th class="px-6 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($attendances as $att)
                        <tr class="hover:bg-[#FFF8EF] transition border-b border-[#F0EEE9]">
                            <td class="px-6 py-4 font-bold text-[#1B1C19]">
                                {{ $att->date ? $att->date->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-[#746D67] font-medium">
                                {{ $att->date ? $att->date->format('l') : '-' }}
                            </td>
                            <td class="px-6 py-4 font-mono font-semibold text-[#1B1C19]">
                                {{ $att->clock_in ?? '--:--:--' }}
                            </td>
                            <td class="px-6 py-4 font-mono font-semibold text-[#1B1C19]">
                                {{ $att->clock_out ?? '--:--:--' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($att->status === 'Present')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">Tepat Waktu</span>
                                @elseif($att->status === 'Late')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-orange-100 text-orange-700">Terlambat</span>
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
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">Tidak ada riwayat presensi.</td>
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
