@extends('layouts.app')

@section('title', 'Manajemen Departemen')
@section('header_title', 'Manajemen Departemen')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- Form Tambah Departemen -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4 h-fit">
        <h2 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3">Tambah Departemen Baru</h2>
        <form action="{{ route('departments.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Departemen *</label>
                <input type="text" name="name" required class="w-full px-4 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 outline-none" placeholder="contoh: Marketing">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 outline-none" placeholder="Keterangan singkat..."></textarea>
            </div>
            <button type="submit" class="w-full py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-xl text-sm shadow transition">
                Simpan Departemen
            </button>
        </form>
    </div>

    <!-- Tabel Daftar Departemen -->
    <div class="md:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="font-bold text-slate-800 text-base">Daftar Departemen</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Nama Departemen</th>
                        <th class="px-6 py-3.5">Deskripsi</th>
                        <th class="px-6 py-3.5">Jumlah Karyawan</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($departments as $dept)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $dept->name }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $dept->description ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 bg-sky-50 text-sky-700 font-bold rounded-lg text-xs">
                                    {{ $dept->employees_count }} Orang
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <form action="{{ route('departments.destroy', $dept) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus departemen ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-800">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada data departemen.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
