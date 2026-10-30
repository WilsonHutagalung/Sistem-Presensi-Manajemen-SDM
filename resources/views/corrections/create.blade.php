@extends('layouts.app')

@section('title', 'Form Koreksi Absensi')
@section('header_title', 'Form Pengajuan Koreksi Presensi')

@section('content')
<div class="max-w-3xl mx-auto stitch-card p-6 sm:p-8 space-y-6">
    <div class="border-b border-[#E8E3DC] pb-5">
        <p class="stitch-eyebrow">Pengajuan baru</p>
        <h2 class="text-2xl font-bold text-[#1B1C19] mt-1">Formulir Koreksi Presensi</h2>
        <p class="text-xs text-[#746D67] mt-1">Ajukan perubahan waktu jika terdapat kesalahan pencatatan presensi.</p>
    </div>

    <form action="{{ route('corrections.store') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-[#4D4540] mb-1">Catatan presensi yang dikoreksi *</label>
            <select name="attendance_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-amber-500 outline-none text-sm">
                <option value="">-- Pilih Catatan Absensi --</option>
                @foreach($attendances as $att)
                    <option value="{{ $att->id }}">
                        {{ $att->date->format('d M Y') }} &bull; In: {{ $att->clock_in ?? '--:--' }} | Out: {{ $att->clock_out ?? '--:--' }} ({{ $att->status }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-[#4D4540] mb-1">Koreksi clock in</label>
                <input type="time" name="requested_clock_in"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-amber-500 outline-none text-sm font-mono">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#4D4540] mb-1">Koreksi clock out</label>
                <input type="time" name="requested_clock_out"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-amber-500 outline-none text-sm font-mono">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-[#4D4540] mb-1">Alasan koreksi *</label>
            <textarea name="reason" rows="4" required placeholder="Jelaskan kendala yang terjadi (misal: Lupa clock out karena rapat lapangan)..."
                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-amber-500 outline-none text-sm">{{ old('reason') }}</textarea>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-[#E8E3DC]">
            <a href="{{ route('corrections.index') }}" class="px-5 py-2 rounded-lg border border-[#D0C4BD] text-[#746D67] hover:bg-[#F5F3EE] font-semibold text-xs">Batal</a>
            <button type="submit" class="stitch-button px-6 py-2.5 text-xs">
                Kirim Pengajuan Koreksi
            </button>
        </div>
    </form>
</div>
@endsection
