@extends('layouts.app')

@section('title', 'Jadwal Hari Kerja')
@section('header_title', 'Pengaturan Hari Kerja Mingguan')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
    <div class="mb-6 border-b border-slate-100 pb-4">
        <h2 class="text-xl font-bold text-slate-800">Hari Kerja Mingguan</h2>
        <p class="text-slate-500 text-sm mt-1">Tentukan hari kerja aktif perusahaan. Hari non-working day tidak akan dihitung sebagai Absent.</p>
    </div>

    <form action="{{ route('schedules.update') }}" method="POST" class="space-y-4">
        @csrf
        <div class="divide-y divide-slate-100 border border-slate-200 rounded-xl overflow-hidden">
            @foreach($schedules as $sched)
                <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition">
                    <div>
                        <span class="font-bold text-slate-800 text-base block">{{ $sched->day_of_week }}</span>
                        <span class="text-xs text-slate-400">
                            @if(in_array($sched->day_of_week, ['Saturday', 'Sunday']))
                                Hari Libur Akhir Pekan Default
                            @else
                                Hari Kerja Utama Perusahaan
                            @endif
                        </span>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="schedules[{{ $sched->id }}][is_working_day]" value="1" 
                            {{ $sched->is_working_day ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        <span class="ml-3 text-sm font-semibold {{ $sched->is_working_day ? 'text-emerald-600' : 'text-slate-400' }}">
                            {{ $sched->is_working_day ? 'Hari Kerja (Work)' : 'Libur (Off)' }}
                        </span>
                    </label>
                </div>
            @endforeach
        </div>

        <div class="pt-4 flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-xl text-sm shadow transition">
                Simpan Jadwal Kerja
            </button>
        </div>
    </form>
</div>
@endsection
