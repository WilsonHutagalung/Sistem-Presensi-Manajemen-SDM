@extends('layouts.app')

@section('title', 'Daftar Pengajuan Cuti')
@section('header_title', 'Pengajuan & Approval Cuti Karyawan')

@section('content')
<div class="space-y-5 max-w-7xl mx-auto">

    <!-- Aksi Header -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3">
        <div>
            <p class="stitch-eyebrow">Pengajuan</p>
            <h2 class="text-2xl font-bold text-[#1B1C19]">Cuti Karyawan</h2>
            <p class="text-xs text-[#746D67] mt-1">Ajukan cuti dan tinjau riwayat persetujuan Anda.</p>
        </div>
        @if(auth()->user()->isEmployee())
            <a href="{{ route('leaves.create') }}" class="stitch-button px-5 py-2.5 text-xs flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Ajukan Cuti Baru
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
                        <th class="px-6 py-3.5">Rentang Tanggal Cuti</th>
                        <th class="px-6 py-3.5">Alasan Cuti</th>
                        <th class="px-6 py-3.5">Status</th>
                        @if(in_array(auth()->user()->role, ['super_admin', 'admin', 'hr_admin', 'manager']))
                            <th class="px-6 py-3.5 text-right">Aksi Approval</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($leaves as $l)
                        <tr class="hover:bg-[#FFF8EF] transition border-b border-[#F0EEE9]">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $l->employee->name ?? '-' }}</div>
                                <div class="text-xs text-slate-400">{{ $l->employee->department->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $l->start_date ? $l->start_date->format('d M Y') : '-' }} &mdash; {{ $l->end_date ? $l->end_date->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 max-w-xs truncate text-slate-700">
                                {{ $l->reason }}
                            </td>
                            <td class="px-6 py-4">
                                @if($l->status === 'Approved')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">Approved</span>
                                @elseif($l->status === 'Rejected')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700">Rejected</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">Pending</span>
                                @endif
                            </td>
                            @if(in_array(auth()->user()->role, ['super_admin', 'admin', 'hr_admin', 'manager']))
                                <td class="px-6 py-4 text-right space-x-2">
                                    @if($l->status === 'Pending')
                                        <form action="{{ route('leaves.approve', $l) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs transition">Approve</button>
                                        </form>
                                        <form action="{{ route('leaves.reject', $l) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg text-xs transition">Reject</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400">Proses Selesai oleh {{ $l->approver->name ?? 'Admin' }}</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada data pengajuan cuti.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $leaves->links() }}
        </div>
    </div>

</div>
@endsection
