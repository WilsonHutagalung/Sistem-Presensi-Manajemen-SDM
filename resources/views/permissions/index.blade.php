@extends('layouts.app')

@section('title', 'Daftar Pengajuan Izin')
@section('header_title', 'Pengajuan & Approval Izin Presensi')

@section('content')
<div class="space-y-5 max-w-7xl mx-auto">

    <!-- Aksi Header -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3">
        <div>
            <p class="stitch-eyebrow">Pengajuan</p>
            <h2 class="text-2xl font-bold text-[#1B1C19]">Izin Presensi</h2>
            <p class="text-xs text-[#746D67] mt-1">Kelola pengajuan izin dan pantau status persetujuannya.</p>
        </div>
        @if(auth()->user()->isEmployee())
            <a href="{{ route('permissions.create') }}" class="stitch-button px-5 py-2.5 text-xs flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Ajukan Izin Baru
            </a>
        @endif
    </div>

    <!-- Tabel -->
    <div class="stitch-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-[#4D4540]">
                <thead class="bg-[#F5F3EE] text-[10px] font-bold uppercase tracking-wider text-[#746D67] border-b border-[#E8E3DC]">
                    <tr>
                        <th class="px-6 py-3.5">Karyawan</th>
                        <th class="px-6 py-3.5">Tanggal Izin</th>
                        <th class="px-6 py-3.5">Alasan</th>
                        <th class="px-6 py-3.5">Lampiran</th>
                        <th class="px-6 py-3.5">Status</th>
                        @if(in_array(auth()->user()->role, ['super_admin', 'admin', 'hr_admin', 'manager']))
                            <th class="px-6 py-3.5 text-right">Aksi Approval</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($permissions as $p)
                        <tr class="hover:bg-[#FFF8EF] transition border-b border-[#F0EEE9]">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $p->employee->name ?? '-' }}</div>
                                <div class="text-xs text-slate-400">{{ $p->employee->department->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $p->date ? $p->date->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 max-w-xs truncate text-slate-700">
                                {{ $p->reason }}
                            </td>
                            <td class="px-6 py-4">
                                @if($p->attachment)
                                    <a href="{{ route('permissions.attachment', $p) }}" target="_blank" class="text-xs font-semibold text-sky-600 hover:underline flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Lihat File
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($p->status === 'Approved')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">Approved</span>
                                @elseif($p->status === 'Rejected')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700">Rejected</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">Pending</span>
                                @endif
                            </td>
                            @if(in_array(auth()->user()->role, ['super_admin', 'admin', 'hr_admin', 'manager']))
                                <td class="px-6 py-4 text-right space-x-2">
                                    @if($p->status === 'Pending')
                                        <form action="{{ route('permissions.approve', $p) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs transition">Approve</button>
                                        </form>
                                        <form action="{{ route('permissions.reject', $p) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg text-xs transition">Reject</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400">Proses Selesai oleh {{ $p->approver->name ?? 'Admin' }}</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">Belum ada data pengajuan izin.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $permissions->links() }}
        </div>
    </div>

</div>
@endsection
