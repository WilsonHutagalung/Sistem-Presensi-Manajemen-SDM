@extends('layouts.app')

@section('title', 'Dashboard Executive — Perusahaan Aressa Golden Farm')
@section('header_title', 'Executive Dashboard')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Header Selamat Datang & Tindakan Cepat -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-[#D9CFC7] shadow-xs">
        <div>
            <h1 class="text-lg font-bold text-[#3A3027] tracking-tight">Selamat Datang, {{ auth()->user()->name }}</h1>
            <p class="text-xs text-slate-600 mt-0.5 font-medium">Ringkasan aktivitas dan statistik kehadiran karyawan hari ini.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('attendance.monitoring') }}" class="px-3.5 py-2 bg-[#C9B59C] hover:bg-[#3A3027] text-white font-semibold rounded-xl text-xs transition shadow-xs">
                Monitoring Live &rarr;
            </a>
            <a href="{{ route('reports.index') }}" class="px-3.5 py-2 bg-[#3A3027] hover:bg-black text-white font-semibold rounded-xl text-xs transition shadow-xs">
                Export Laporan
            </a>
        </div>
    </div>

    <!-- Grid Kartu Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
        <!-- Total Karyawan -->
        <div class="bg-white p-5 rounded-2xl border border-[#D9CFC7] shadow-xs">
            <div class="flex items-center space-x-2 text-xs font-semibold text-[#C9B59C]">
                <span class="w-2.5 h-2.5 rounded-full bg-[#C9B59C]"></span>
                <span>Total Karyawan</span>
            </div>
            <p class="text-2xl font-extrabold text-[#3A3027] mt-2">{{ $totalEmployees }}</p>
            <span class="text-[11px] text-slate-500 font-medium block mt-1">Aktif di Sistem</span>
        </div>

        <!-- Hadir -->
        <div class="bg-white p-5 rounded-2xl border border-[#D9CFC7] shadow-xs">
            <div class="flex items-center space-x-2 text-xs font-semibold text-[#3A3027]">
                <span class="w-2.5 h-2.5 rounded-full bg-[#D9CFC7]"></span>
                <span>Hadir Hari Ini</span>
            </div>
            <p class="text-2xl font-extrabold text-[#3A3027] mt-2">{{ $totalHadir }}</p>
            <span class="text-[11px] text-[#C9B59C] font-bold block mt-1">Tepat Waktu</span>
        </div>

        <!-- Terlambat -->
        <div class="bg-white p-5 rounded-2xl border border-[#D9CFC7] shadow-xs">
            <div class="flex items-center space-x-2 text-xs font-semibold text-amber-700">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                <span>Terlambat</span>
            </div>
            <p class="text-2xl font-extrabold text-[#3A3027] mt-2">{{ $totalTerlambat }}</p>
            <span class="text-[11px] text-amber-700 font-medium block mt-1">Lewat Tolerance</span>
        </div>

        <!-- Izin -->
        <div class="bg-white p-5 rounded-2xl border border-[#D9CFC7] shadow-xs">
            <div class="flex items-center space-x-2 text-xs font-semibold text-sky-700">
                <span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span>
                <span>Total Izin</span>
            </div>
            <p class="text-2xl font-extrabold text-[#3A3027] mt-2">{{ $totalIzin }}</p>
            <span class="text-[11px] text-sky-700 font-medium block mt-1">Izin Disetujui</span>
        </div>

        <!-- Cuti -->
        <div class="bg-white p-5 rounded-2xl border border-[#D9CFC7] shadow-xs">
            <div class="flex items-center space-x-2 text-xs font-semibold text-purple-700">
                <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                <span>Total Cuti</span>
            </div>
            <p class="text-2xl font-extrabold text-[#3A3027] mt-2">{{ $totalCuti }}</p>
            <span class="text-[11px] text-purple-700 font-medium block mt-1">Cuti Disetujui</span>
        </div>

        <!-- Tidak Hadir -->
        <div class="bg-white p-5 rounded-2xl border border-[#D9CFC7] shadow-xs">
            <div class="flex items-center space-x-2 text-xs font-semibold text-rose-700">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                <span>Tidak Hadir</span>
            </div>
            <p class="text-2xl font-extrabold text-[#3A3027] mt-2">{{ $totalTidakHadir }}</p>
            <span class="text-[11px] text-rose-700 font-medium block mt-1">Belum Absen</span>
        </div>
    </div>

    <!-- Tabel Aktivitas Presensi Terbaru -->
    <div class="bg-white rounded-2xl border border-[#D9CFC7] shadow-xs overflow-hidden">
        <div class="p-5 border-b border-[#D9CFC7] flex items-center justify-between">
            <div>
                <h2 class="font-bold text-[#3A3027] text-sm tracking-tight">Aktivitas Presensi Terkini</h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Catatan presensi terbaru yang masuk ke dalam sistem.</p>
            </div>
            <a href="{{ route('attendance.monitoring') }}" class="text-xs font-bold text-[#C9B59C] hover:text-[#3A3027]">Lihat Semua &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-[#EFE9E3] text-[11px] font-bold uppercase tracking-wider text-[#3A3027] border-b border-[#D9CFC7]">
                    <tr>
                        <th class="px-5 py-3.5">Karyawan</th>
                        <th class="px-5 py-3.5">Departemen & Jabatan</th>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Clock In</th>
                        <th class="px-5 py-3.5">Clock Out</th>
                        <th class="px-5 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D9CFC7]/60">
                    @forelse($recentAttendances as $att)
                        <tr class="hover:bg-[#EFE9E3]/50 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-[#EFE9E3] text-[#3A3027] font-bold text-xs flex items-center justify-center border border-[#D9CFC7] flex-shrink-0">
                                        {{ strtoupper(substr($att->employee->name ?? 'K', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-[#3A3027]">{{ $att->employee->name ?? '-' }}</div>
                                        <div class="font-mono text-[10px] text-slate-500">{{ $att->employee->employee_number ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="text-[#3A3027] font-bold">{{ $att->employee->department->name ?? '-' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $att->employee->position->name ?? '-' }}</div>
                            </td>
                            <td class="px-5 py-3.5 text-slate-700 font-medium">
                                {{ $att->date ? $att->date->format('d M Y') : '-' }}
                            </td>
                            <td class="px-5 py-3.5 font-mono font-bold text-[#3A3027]">
                                {{ $att->clock_in ?? '--:--' }}
                            </td>
                            <td class="px-5 py-3.5 font-mono font-bold text-[#3A3027]">
                                {{ $att->clock_out ?? '--:--' }}
                            </td>
                            <td class="px-5 py-3.5">
                                @if($att->status === 'Present')
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-[#D9CFC7]/40 text-[#3A3027] border border-[#D9CFC7]">Present</span>
                                @elseif($att->status === 'Late')
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-amber-50 text-amber-900 border border-amber-200">Late</span>
                                @elseif($att->status === 'Permission')
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-sky-50 text-sky-900 border border-sky-200">Permission</span>
                                @elseif($att->status === 'Leave')
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-purple-50 text-purple-900 border border-purple-200">Leave</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-rose-50 text-rose-900 border border-rose-200">Absent</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-slate-500 text-xs">
                                Belum ada data aktivitas presensi terbaru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
