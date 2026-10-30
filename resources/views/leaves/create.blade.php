@extends('layouts.app')

@section('title', 'Form Pengajuan Cuti')
@section('header_title', 'Form Pengajuan Cuti Karyawan')

@section('content')
<div class="max-w-3xl mx-auto stitch-card p-6 sm:p-8 space-y-6">
    <div class="border-b border-[#E8E3DC] pb-5">
        <p class="stitch-eyebrow">Pengajuan baru</p>
        <h2 class="text-2xl font-bold text-[#1B1C19] mt-1">Formulir Pengajuan Cuti</h2>
        <p class="text-xs text-[#746D67] mt-1">Tentukan periode cuti dan alasan pengajuan Anda.</p>
    </div>

    <form action="{{ route('leaves.store') }}" method="POST" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-[#4D4540] mb-1">Tanggal mulai *</label>
                <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-purple-500 outline-none text-sm">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#4D4540] mb-1">Tanggal selesai *</label>
                <input type="date" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-purple-500 outline-none text-sm">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-[#4D4540] mb-1">Alasan pengajuan *</label>
            <textarea name="reason" rows="4" required placeholder="Tuliskan keperluan cuti Anda..."
                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-purple-500 outline-none text-sm">{{ old('reason') }}</textarea>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-[#E8E3DC]">
            <a href="{{ route('leaves.index') }}" class="px-5 py-2 rounded-lg border border-[#D0C4BD] text-[#746D67] hover:bg-[#F5F3EE] font-semibold text-xs">Batal</a>
            <button type="submit" class="stitch-button px-6 py-2.5 text-xs">
                Kirim Pengajuan Cuti
            </button>
        </div>
    </form>
</div>
@endsection
