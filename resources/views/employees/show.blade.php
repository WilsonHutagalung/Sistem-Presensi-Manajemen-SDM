@extends('layouts.app')

@section('title', 'Detail Karyawan — Perusahaan Aressa Golden Farm')
@section('header_title', 'Profil & Detail Karyawan')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Kartu Header Profil -->
    <div class="bg-white rounded-2xl border border-[#D9CFC7] p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-[#C9B59C] text-white text-2xl sm:text-3xl font-bold flex items-center justify-center flex-shrink-0 shadow-sm">
                {{ strtoupper(substr($employee->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0 text-center sm:text-left space-y-1">
                <div class="flex flex-col sm:flex-row items-center sm:items-center gap-2">
                    <h1 class="text-xl font-bold text-[#3A3027]">{{ $employee->name }}</h1>
                    @if($employee->status === 'active')
                        <span class="px-2.5 py-0.5 bg-[#D9CFC7]/40 text-[#3A3027] text-[11px] font-bold rounded-full border border-[#D9CFC7]">Aktif</span>
                    @else
                        <span class="px-2.5 py-0.5 bg-slate-100 text-slate-500 text-[11px] font-bold rounded-full border border-slate-200">Nonaktif</span>
                    @endif
                </div>
                <p class="text-xs font-mono text-slate-500">NIK: {{ $employee->employee_number }}</p>
                <p class="text-xs text-slate-600 font-medium">
                    {{ $employee->department->name ?? 'Tanpa Departemen' }} &bull; {{ $employee->position->name ?? 'Tanpa Jabatan' }}
                </p>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('employees.edit', $employee) }}" class="px-4 py-2 bg-[#C9B59C] hover:bg-[#3A3027] text-white text-xs font-bold rounded-xl shadow-xs transition">Edit Profil</a>
                <a href="{{ route('employees.index') }}" class="px-4 py-2 border border-[#D9CFC7] text-[#3A3027] text-xs font-bold rounded-xl hover:bg-[#EFE9E3] transition">Kembali</a>
            </div>
        </div>
    </div>

    <!-- Grid Informasi Detail -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-[#D9CFC7] shadow-sm space-y-4">
            <h3 class="font-bold text-[#3A3027] text-sm border-b border-[#D9CFC7] pb-3">Informasi Pribadi</h3>
            <div class="space-y-3 text-xs">
                <div class="flex justify-between gap-4">
                    <span class="text-slate-500 flex-shrink-0">Email System</span>
                    <span class="font-semibold text-[#3A3027] text-right truncate">{{ $employee->user->email ?? '-' }}</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-slate-500 flex-shrink-0">Nomor Telepon</span>
                    <span class="font-semibold text-[#3A3027]">{{ $employee->phone ?? '-' }}</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-slate-500 flex-shrink-0">Jenis Kelamin</span>
                    <span class="font-semibold text-[#3A3027]">{{ $employee->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-slate-500 flex-shrink-0">Tanggal Lahir</span>
                    <span class="font-semibold text-[#3A3027]">{{ $employee->birth_date ? $employee->birth_date->format('d M Y') : '-' }}</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-slate-500 flex-shrink-0">Alamat</span>
                    <span class="font-semibold text-[#3A3027] text-right">{{ $employee->address ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-[#D9CFC7] shadow-sm space-y-4">
            <h3 class="font-bold text-[#3A3027] text-sm border-b border-[#D9CFC7] pb-3">Informasi Kepegawaian</h3>
            <div class="space-y-3 text-xs">
                <div class="flex justify-between gap-4">
                    <span class="text-slate-500 flex-shrink-0">Role Sistem</span>
                    <span class="font-bold text-[#C9B59C] capitalize">{{ str_replace('_', ' ', $employee->user->role ?? '-') }}</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-slate-500 flex-shrink-0">Departemen</span>
                    <span class="font-semibold text-[#3A3027]">{{ $employee->department->name ?? '-' }}</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-slate-500 flex-shrink-0">Jabatan</span>
                    <span class="font-semibold text-[#3A3027]">{{ $employee->position->name ?? '-' }}</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-slate-500 flex-shrink-0">Tanggal Bergabung</span>
                    <span class="font-semibold text-[#3A3027]">{{ $employee->join_date ? $employee->join_date->format('d M Y') : '-' }}</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-slate-500 flex-shrink-0">Shift Kerja</span>
                    <span class="font-bold text-[#C9B59C]">{{ $employee->currentShift()->name ?? 'Shift Pagi' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Kehadiran Terbaru -->
    <div class="bg-white rounded-2xl border border-[#D9CFC7] shadow-sm overflow-hidden">
        <div class="p-5 border-b border-[#D9CFC7]">
            <h3 class="font-bold text-[#3A3027] text-sm">Riwayat Presensi Terbaru</h3>
        </div>

        <!-- Tabel Desktop -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-[#EFE9E3] text-[11px] font-bold uppercase tracking-wider text-[#3A3027] border-b border-[#D9CFC7]">
                    <tr>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Clock In</th>
                        <th class="px-5 py-3.5">Clock Out</th>
                        <th class="px-5 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D9CFC7]/60">
                    @forelse($employee->attendances as $att)
                        <tr class="hover:bg-[#EFE9E3]/50 transition-colors">
                            <td class="px-5 py-3 font-medium text-[#3A3027]">{{ $att->date->format('d M Y') }}</td>
                            <td class="px-5 py-3 font-mono text-slate-700">{{ $att->clock_in ?? '--:--' }}</td>
                            <td class="px-5 py-3 font-mono text-slate-700">{{ $att->clock_out ?? '--:--' }}</td>
                            <td class="px-5 py-3">
                                @if($att->status === 'Present')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#D9CFC7]/40 text-[#3A3027] border border-[#D9CFC7]">Present</span>
                                @elseif($att->status === 'Late')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200">Late</span>
                                @elseif($att->status === 'Permission')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-sky-50 text-sky-800 border border-sky-200">Permission</span>
                                @elseif($att->status === 'Leave')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-purple-50 text-purple-800 border border-purple-200">Leave</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-800 border border-rose-200">Absent</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-8 text-center text-slate-400 text-xs">Belum ada catatan presensi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Kartu Kehadiran Seluler -->
        <div class="sm:hidden divide-y divide-[#D9CFC7]/60">
            @forelse($employee->attendances as $att)
                <div class="px-4 py-3 flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold text-[#3A3027]">{{ $att->date->format('d M Y') }}</div>
                        <div class="text-[10px] text-slate-500 mt-0.5 font-mono">
                            In: {{ $att->clock_in ?? '--:--' }} &bull; Out: {{ $att->clock_out ?? '--:--' }}
                        </div>
                    </div>
                    <div>
                        @if($att->status === 'Present')
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-[#D9CFC7]/40 text-[#3A3027] border border-[#D9CFC7]">Present</span>
                        @elseif($att->status === 'Late')
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200">Late</span>
                        @elseif($att->status === 'Permission')
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-sky-50 text-sky-800 border border-sky-200">Izin</span>
                        @elseif($att->status === 'Leave')
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-purple-50 text-purple-800 border border-purple-200">Cuti</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-800 border border-rose-200">Absent</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-5 py-8 text-center text-slate-400 text-xs">Belum ada catatan presensi.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection
