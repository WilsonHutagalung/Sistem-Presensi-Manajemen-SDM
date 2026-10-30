@extends('layouts.app')

@section('title', 'Presensi Saya — Perusahaan Aressa Golden Farm')
@section('header_title', 'Dashboard Presensi Karyawan')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">

    <!-- Kartu Profil & Shift -->
    <div class="bg-white rounded-2xl border border-[#D9CFC7] p-6 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-start space-x-4">
            <div class="w-12 h-12 rounded-xl bg-[#C9B59C] text-white font-bold text-lg flex items-center justify-center flex-shrink-0 shadow-xs">
                {{ strtoupper(substr($employee->name, 0, 1)) }}
            </div>
            <div class="space-y-1">
                <div class="flex items-center space-x-2">
                    <h1 class="text-lg font-bold text-[#3A3027] tracking-tight">{{ $employee->name }}</h1>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-[#D9CFC7]/30 text-[#3A3027] border border-[#D9CFC7]">
                        {{ ucfirst($employee->status) }}
                    </span>
                </div>
                <p class="text-xs text-slate-600 font-medium">
                    NIK: <span class="font-mono text-[#3A3027] font-bold">{{ $employee->employee_number }}</span> &bull; 
                    Departemen: <span class="text-[#3A3027] font-bold">{{ $employee->department->name ?? '-' }}</span> &bull; 
                    Jabatan: <span class="text-[#3A3027] font-bold">{{ $employee->position->name ?? '-' }}</span>
                </p>
            </div>
        </div>

        <div class="bg-[#EFE9E3] border border-[#D9CFC7] rounded-xl p-3.5 flex items-center justify-between md:justify-end space-x-4 min-w-[220px]">
            <div>
                <span class="text-[10px] font-bold text-[#C9B59C] uppercase tracking-wider block">Shift Kerja</span>
                <span class="text-xs font-bold text-[#3A3027] block mt-0.5">{{ $shift->name ?? 'Shift Pagi' }}</span>
            </div>
            <div class="text-right">
                <span class="font-mono text-xs font-bold text-[#3A3027] bg-white px-2.5 py-1 rounded-md border border-[#D9CFC7] inline-block">
                    {{ substr($shift->start_time ?? '08:00', 0, 5) }} - {{ substr($shift->end_time ?? '17:00', 0, 5) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Widget Aksi Absensi -->
    <div class="bg-white rounded-2xl border border-[#D9CFC7] p-8 shadow-xs text-center">
        <div class="max-w-md mx-auto space-y-6">
            
            <div class="space-y-1">
                <span class="text-[11px] font-bold uppercase tracking-widest text-[#C9B59C] block">Waktu Presensi Real-Time</span>
                <div class="font-mono text-4xl sm:text-5xl font-extrabold text-[#3A3027] tracking-tight py-1" id="main-clock">
                    --:--:-- WIB
                </div>
                <p class="text-xs text-slate-500 font-medium">{{ \Carbon\Carbon::now()->format('l, d F Y') }}</p>
            </div>

            <!-- Kartu Clock In / Clock Out Hari Ini -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-[#EFE9E3] p-4 rounded-xl border border-[#D9CFC7] text-left">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#C9B59C]">Clock In</span>
                        <span class="w-2.5 h-2.5 rounded-full {{ $todayAttendance && $todayAttendance->clock_in ? 'bg-[#C9B59C]' : 'bg-slate-300' }}"></span>
                    </div>
                    <span class="font-mono text-base sm:text-lg font-bold text-[#3A3027] mt-1 block">
                        {{ $todayAttendance->clock_in ?? '--:--' }}
                    </span>
                    <span class="text-[10px] text-slate-500 block">Jam Masuk</span>
                </div>

                <div class="bg-[#EFE9E3] p-4 rounded-xl border border-[#D9CFC7] text-left">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#C9B59C]">Clock Out</span>
                        <span class="w-2.5 h-2.5 rounded-full {{ $todayAttendance && $todayAttendance->clock_out ? 'bg-[#C9B59C]' : 'bg-slate-300' }}"></span>
                    </div>
                    <span class="font-mono text-base sm:text-lg font-bold text-[#3A3027] mt-1 block">
                        {{ $todayAttendance->clock_out ?? '--:--' }}
                    </span>
                    <span class="text-[10px] text-slate-500 block">Jam Pulang</span>
                </div>
            </div>

            <!-- Pil Status -->
            @if($todayAttendance && $todayAttendance->status)
                <div class="pt-1">
                    @if($todayAttendance->status === 'Present')
                        <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-[#D9CFC7]/40 text-[#3A3027] border border-[#D9CFC7]">Status: Tepat Waktu (Present)</span>
                    @elseif($todayAttendance->status === 'Late')
                        <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-900 border border-amber-200">Status: Terlambat (Late)</span>
                    @elseif($todayAttendance->status === 'Permission')
                        <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-900 border border-sky-200">Status: Izin Disetujui (Permission)</span>
                    @elseif($todayAttendance->status === 'Leave')
                        <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-900 border border-purple-200">Status: Cuti Disetujui (Leave)</span>
                    @else
                        <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-900 border border-rose-200">Status: Tidak Hadir (Absent)</span>
                    @endif
                </div>
            @endif

            <!-- Tombol Clock In / Clock Out -->
            <div class="pt-2 flex justify-center">
                @if(!$todayAttendance || !$todayAttendance->clock_in)
                    <form action="{{ route('attendance.clockIn') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full py-3.5 bg-[#C9B59C] hover:bg-[#3A3027] active:scale-[0.98] text-white font-bold rounded-xl shadow-xs transition-all flex items-center justify-center space-x-2 text-sm tracking-wide">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            <span>CLOCK IN MASUK</span>
                        </button>
                    </form>
                @elseif(!$todayAttendance->clock_out)
                    <form action="{{ route('attendance.clockOut') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full py-3.5 bg-[#8f5855] hover:bg-[#724340] active:scale-[0.98] text-white font-bold rounded-xl shadow-xs transition-all flex items-center justify-center space-x-2 text-sm tracking-wide">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span>CLOCK OUT PULANG</span>
                        </button>
                    </form>
                @else
                    <div class="w-full py-3 bg-[#EFE9E3] text-[#3A3027] rounded-xl font-bold text-xs inline-flex items-center justify-center border border-[#D9CFC7]">
                        <svg class="w-4 h-4 text-[#C9B59C] mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Presensi Hari Ini Selesai
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Ringkasan Statistik Bulanan -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-[#D9CFC7] shadow-xs">
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#C9B59C]"></span>
                <span class="text-xs font-bold text-[#C9B59C] uppercase tracking-wider">Hadir Bulan Ini</span>
            </div>
            <span class="text-2xl font-extrabold text-[#3A3027] mt-2 block">{{ $totalHadirBulanIni }} Hari</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-[#D9CFC7] shadow-xs">
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Terlambat</span>
            </div>
            <span class="text-2xl font-extrabold text-[#3A3027] mt-2 block">{{ $totalTerlambatBulanIni }} Hari</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-[#D9CFC7] shadow-xs">
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span>
                <span class="text-xs font-bold text-sky-700 uppercase tracking-wider">Izin Disetujui</span>
            </div>
            <span class="text-2xl font-extrabold text-[#3A3027] mt-2 block">{{ $totalIzinBulanIni }} Hari</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-[#D9CFC7] shadow-xs">
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                <span class="text-xs font-bold text-purple-700 uppercase tracking-wider">Cuti Disetujui</span>
            </div>
            <span class="text-2xl font-extrabold text-[#3A3027] mt-2 block">{{ $totalCutiBulanIni }} Hari</span>
        </div>
    </div>

</div>

<script>
    function updateMainClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const el = document.getElementById('main-clock');
        if (el) {
            el.textContent = `${hours}:${minutes}:${seconds} WIB`;
        }
    }
    setInterval(updateMainClock, 1000);
    updateMainClock();
</script>
@endsection
