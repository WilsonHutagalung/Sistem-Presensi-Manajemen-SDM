@extends('layouts.app')

@section('title', 'Form Pengajuan Izin')
@section('header_title', 'Form Pengajuan Izin Presensi')

@section('content')
<div class="max-w-3xl mx-auto stitch-card p-6 sm:p-8 space-y-6">
    <div class="border-b border-[#E8E3DC] pb-5">
        <p class="stitch-eyebrow">Pengajuan baru</p>
        <h2 class="text-2xl font-bold text-[#1B1C19] mt-1">Formulir Izin Presensi</h2>
        <p class="text-xs text-[#746D67] mt-1">Isi detail pengajuan dengan jelas agar dapat segera ditinjau supervisor.</p>
    </div>

    <form action="{{ route('permissions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-[#4D4540] mb-1">Tanggal izin *</label>
            <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-sky-500 outline-none text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold text-[#4D4540] mb-1">Alasan pengajuan *</label>
            <textarea name="reason" rows="4" required placeholder="Jelaskan alasan izin Anda secara lengkap..."
                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-sky-500 outline-none text-sm">{{ old('reason') }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-semibold text-[#4D4540] mb-1">Lampiran pendukung <span class="font-normal text-[#746D67]">(opsional)</span></label>
            <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png"
                class="w-full px-4 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 outline-none">
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-[#E8E3DC]">
            <a href="{{ route('permissions.index') }}" class="px-5 py-2 rounded-lg border border-[#D0C4BD] text-[#746D67] hover:bg-[#F5F3EE] font-semibold text-xs">Batal</a>
            <button type="submit" class="stitch-button px-6 py-2.5 text-xs">
                Kirim Pengajuan Izin
            </button>
        </div>
    </form>
</div>
@endsection
