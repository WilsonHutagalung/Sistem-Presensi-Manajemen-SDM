@extends('layouts.app')

@section('title', 'Koreksi Absensi')
@section('header_title', 'Pengajuan & Approval Koreksi Presensi')

@section('content')
<div class="space-y-5 max-w-7xl mx-auto">

    <!-- Aksi Header -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3">
        <div>
            <p class="stitch-eyebrow">Pengajuan</p>
            <h2 class="text-2xl font-bold text-[#1B1C19]">Koreksi Presensi</h2>
            <p class="text-xs text-[#746D67] mt-1">Perbaiki catatan presensi dan pantau proses peninjauannya.</p>
        </div>
        @if(auth()->user()->isEmployee())
            <a href="{{ route('corrections.create') }}" class="stitch-button px-5 py-2.5 text-xs flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Ajukan Koreksi Presensi
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
                        <th class="px-6 py-3.5">Tanggal Presensi</th>
                        <th class="px-6 py-3.5">Usulan Clock In / Out</th>
                        <th class="px-6 py-3.5">Alasan Koreksi</th>
                        <th class="px-6 py-3.5">Status</th>
                        @if(in_array(auth()->user()->role, ['super_admin', 'admin', 'hr_admin', 'manager']))
                            <th class="px-6 py-3.5 text-right">Aksi Approval</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($corrections as $c)
                        <tr class="hover:bg-[#FFF8EF] transition border-b border-[#F0EEE9]">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $c->employee->name ?? '-' }}</div>
                                <div class="text-xs text-slate-400">{{ $c->employee->department->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $c->attendance && $c->attendance->date ? $c->attendance->date->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 font-mono text-xs font-semibold text-slate-700">
                                In: {{ $c->requested_clock_in ?? '-' }} <br>
                                Out: {{ $c->requested_clock_out ?? '-' }}
                            </td>
                            <td class="px-6 py-4 max-w-xs truncate text-slate-700">
                                {{ $c->reason }}
                            </td>
                            <td class="px-6 py-4">
                                @if($c->status === 'Approved')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">Approved</span>
                                @elseif($c->status === 'Rejected')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700">Rejected</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">Pending</span>
                                @endif
                            </td>
                            @if(in_array(auth()->user()->role, ['super_admin', 'admin', 'hr_admin', 'manager']))
                                <td class="px-6 py-4 text-right space-x-2">
                                    @if($c->status === 'Pending')
                                        <form action="{{ route('corrections.approve', $c) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs transition">Approve</button>
                                        </form>
                                        <form action="{{ route('corrections.reject', $c) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg text-xs transition">Reject</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400">Ditinjau oleh {{ $c->reviewer->name ?? 'Admin' }}</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">Belum ada pengajuan koreksi presensi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $corrections->links() }}
        </div>
    </div>

</div>
@endsection
