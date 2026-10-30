@extends('layouts.app')

@section('title', 'Manajemen Karyawan — Perusahaan Aressa Golden Farm')
@section('header_title', 'Manajemen Data Karyawan')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Aksi Header & Filter Pencarian -->
    <div class="bg-white p-5 rounded-2xl border border-[#D9CFC7] shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <form action="{{ route('employees.index') }}" method="GET" class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-2.5 flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIK, HP..."
                    class="px-3.5 py-2 border border-[#D9CFC7] rounded-xl text-xs outline-none focus:ring-2 focus:ring-[#C9B59C]/40 focus:border-[#C9B59C] w-full sm:w-52 bg-[#EFE9E3]/30 transition">

                <select name="department_id" class="px-3.5 py-2 border border-[#D9CFC7] rounded-xl text-xs outline-none focus:ring-2 focus:ring-[#C9B59C]/40 focus:border-[#C9B59C] bg-white transition">
                    <option value="">-- Semua Departemen --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>

                <select name="status" class="px-3.5 py-2 border border-[#D9CFC7] rounded-xl text-xs outline-none focus:ring-2 focus:ring-[#C9B59C]/40 focus:border-[#C9B59C] bg-white transition">
                    <option value="">-- Semua Status --</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-[#C9B59C] hover:bg-[#3A3027] text-white rounded-xl text-xs font-semibold transition">
                    Filter Data
                </button>
            </form>

            <a href="{{ route('employees.create') }}" class="px-4 py-2.5 bg-[#3A3027] hover:bg-black text-white font-semibold rounded-xl text-xs shadow-xs transition flex items-center justify-center whitespace-nowrap flex-shrink-0">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Tambah Karyawan
            </a>
        </div>
    </div>

    <!-- Tabel (Desktop) -->
    <div class="bg-white rounded-2xl border border-[#D9CFC7] shadow-sm overflow-hidden">
        <!-- Tabel Desktop -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-[#EFE9E3] text-[11px] font-bold uppercase tracking-wider text-[#3A3027] border-b border-[#D9CFC7]">
                    <tr>
                        <th class="px-5 py-3.5">NIK</th>
                        <th class="px-5 py-3.5">Nama & Email</th>
                        <th class="px-5 py-3.5">Departemen</th>
                        <th class="px-5 py-3.5">Jabatan</th>
                        <th class="px-5 py-3.5">Role Sistem</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D9CFC7]/60">
                    @forelse($employees as $emp)
                        <tr class="hover:bg-[#EFE9E3]/50 transition-colors">
                            <td class="px-5 py-3.5 font-mono font-semibold text-[#3A3027]">{{ $emp->employee_number }}</td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center space-x-2.5">
                                    <div class="w-8 h-8 rounded-full bg-[#D9CFC7] text-[#3A3027] font-bold text-xs flex items-center justify-center flex-shrink-0">
                                        {{ strtoupper(substr($emp->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-[#3A3027]">{{ $emp->name }}</div>
                                        <div class="text-[10px] text-slate-500 font-mono">{{ $emp->user->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 font-medium text-[#3A3027]">{{ $emp->department->name ?? '-' }}</td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $emp->position->name ?? '-' }}</td>
                            <td class="px-5 py-3.5">
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-[#EFE9E3] text-[#3A3027] border border-[#D9CFC7] capitalize">
                                    {{ str_replace('_', ' ', $emp->user->role ?? 'employee') }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                @if($emp->status === 'active')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#D9CFC7]/40 text-[#3A3027] border border-[#D9CFC7]">Aktif</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-500 border border-slate-200">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="flex items-center justify-end space-x-3">
                                    <a href="{{ route('employees.show', $emp) }}" class="text-[11px] font-bold text-[#C9B59C] hover:text-[#3A3027] transition">Detail</a>
                                    <a href="{{ route('employees.edit', $emp) }}" class="text-[11px] font-bold text-[#3A3027] hover:text-black transition">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-slate-400 text-xs">Tidak ada data karyawan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Daftar Kartu Seluler -->
        <div class="md:hidden divide-y divide-[#D9CFC7]/60">
            @forelse($employees as $emp)
                <div class="p-4 hover:bg-[#EFE9E3]/50 transition-colors">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start space-x-3 flex-1 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-[#D9CFC7] text-[#3A3027] font-bold text-sm flex items-center justify-center flex-shrink-0 border border-[#D9CFC7]">
                                {{ strtoupper(substr($emp->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-[#3A3027] text-sm truncate">{{ $emp->name }}</div>
                                <div class="font-mono text-[10px] text-slate-500 mt-0.5">{{ $emp->employee_number }}</div>
                                <div class="text-[10px] text-slate-500 truncate mt-0.5">{{ $emp->user->email ?? '-' }}</div>
                                <div class="flex flex-wrap items-center gap-1.5 mt-2">
                                    <span class="text-[10px] font-medium text-slate-600 bg-[#EFE9E3] border border-[#D9CFC7] px-2 py-0.5 rounded-md">{{ $emp->department->name ?? '-' }}</span>
                                    <span class="text-[10px] font-medium text-slate-600 bg-[#EFE9E3] border border-[#D9CFC7] px-2 py-0.5 rounded-md">{{ $emp->position->name ?? '-' }}</span>
                                    @if($emp->status === 'active')
                                        <span class="text-[10px] font-bold bg-[#D9CFC7]/40 text-[#3A3027] border border-[#D9CFC7] px-2 py-0.5 rounded-full">Aktif</span>
                                    @else
                                        <span class="text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200 px-2 py-0.5 rounded-full">Nonaktif</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-2 flex-shrink-0">
                            <a href="{{ route('employees.show', $emp) }}" class="text-[11px] font-bold text-[#C9B59C] hover:text-[#3A3027] transition">Detail</a>
                            <a href="{{ route('employees.edit', $emp) }}" class="text-[11px] font-bold text-[#3A3027] hover:text-black transition">Edit</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 text-xs">Tidak ada data karyawan.</div>
            @endforelse
        </div>

        <!-- Paginasi -->
        <div class="p-4 border-t border-[#D9CFC7]">
            {{ $employees->links() }}
        </div>
    </div>

</div>
@endsection
