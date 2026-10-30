@extends('layouts.app')

@section('title', 'Manajemen Shift Kerja')
@section('header_title', 'Manajemen Shift & Penugasan Shift')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Form Tambah Shift -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4 h-fit">
            <h2 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3">Buat Shift Baru</h2>
            <form action="{{ route('shifts.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Shift *</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 outline-none" placeholder="contoh: Shift Pagi">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jam Masuk *</label>
                        <input type="time" name="start_time" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm font-mono focus:ring-2 focus:ring-sky-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jam Pulang *</label>
                        <input type="time" name="end_time" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm font-mono focus:ring-2 focus:ring-sky-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Toleransi Keterlambatan (Menit) *</label>
                    <input type="number" name="late_tolerance" value="15" required min="0" class="w-full px-4 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Status Shift *</label>
                    <select name="status" required class="w-full px-4 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 outline-none">
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                </div>
                <button type="submit" class="w-full py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-xl text-sm shadow transition">
                    Simpan Shift
                </button>
            </form>
        </div>

        <!-- Daftar Shift & Form Penugasan -->
        <div class="md:col-span-2 space-y-6">

            <!-- Tabel Shift -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="font-bold text-slate-800 text-base">Daftar Shift Kerja</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-400 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">Nama Shift</th>
                                <th class="px-6 py-3.5">Jam Kerja</th>
                                <th class="px-6 py-3.5">Toleransi Late</th>
                                <th class="px-6 py-3.5">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($shifts as $shift)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 font-bold text-slate-800">{{ $shift->name }}</td>
                                    <td class="px-6 py-4 font-mono font-semibold text-slate-700">
                                        {{ substr($shift->start_time, 0, 5) }} - {{ substr($shift->end_time, 0, 5) }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-amber-600">
                                        {{ $shift->late_tolerance }} menit
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($shift->status === 'active')
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">Aktif</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada shift kerja.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Kartu Penugasan Shift -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h2 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3">Tugaskan Shift ke Karyawan</h2>
                <form action="{{ route('shifts.assign') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Karyawan *</label>
                        <select name="employee_id" required class="w-full px-4 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 outline-none">
                            <option value="">-- Pilih --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_number }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Shift *</label>
                        <select name="shift_id" required class="w-full px-4 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 outline-none">
                            <option value="">-- Pilih --</option>
                            @foreach($shifts as $s)
                                <option value="{{ $s->id }}">{{ $s->name }} ({{ substr($s->start_time, 0, 5) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Efektif *</label>
                        <input type="date" name="effective_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 outline-none">
                    </div>
                    <div class="sm:col-span-3">
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-sm shadow transition">
                            Simpan Penugasan Shift
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>

</div>
@endsection
