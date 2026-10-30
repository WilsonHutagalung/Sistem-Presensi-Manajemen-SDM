<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perusahaan Aressa Golden Farm — Presensi Karyawan')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --ink: #1b1c19;
            --muted: #746d67;
            --line: #d0c4bd;
            --surface: #ffffff;
            --canvas: #fbf9f4;
            --accent: #fe932c;
            --accent-dark: #904d00;
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--canvas); color: var(--ink); }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d7d0c8; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--accent); }
        [class~="bg-[#EFE9E3]"] { background-color: var(--canvas) !important; }
        [class~="bg-[#C9B59C]"] { background-color: var(--accent) !important; }
        [class~="text-[#C9B59C]"] { color: var(--accent-dark) !important; }
        [class~="text-[#3A3027]"] { color: var(--ink) !important; }
        [class~="border-[#D9CFC7]"] { border-color: var(--line) !important; }
        [class~="bg-[#D9CFC7]"] { background-color: #eee8e0 !important; }
        input, select, textarea { border-color: var(--line) !important; background-color: #fff; border-radius: .5rem; }
        input:focus, select:focus, textarea:focus { border-color: var(--accent) !important; --tw-ring-color: rgb(255 148 24 / .2) !important; }
        table thead { background-color: #fbfaf8 !important; }
        table tbody tr:hover { background-color: #fffaf3 !important; }
        .shadow-xs { box-shadow: 0 1px 3px rgb(33 29 26 / .04); }
        .bg-sky-600, .bg-purple-600, .bg-amber-600, .bg-emerald-600 { background-color: var(--accent) !important; }
        .hover\:bg-sky-700:hover, .hover\:bg-purple-700:hover, .hover\:bg-amber-700:hover, .hover\:bg-emerald-700:hover { background-color: #c96e08 !important; }
        .text-sky-600, .text-purple-600, .text-amber-600 { color: var(--accent-dark) !important; }
        .focus\:ring-sky-500:focus, .focus\:ring-purple-500:focus, .focus\:ring-amber-500:focus { --tw-ring-color: rgb(254 147 44 / .25) !important; }
        .stitch-card { background: var(--surface); border: 1px solid rgb(208 196 189 / .45); border-radius: .75rem; box-shadow: 0 1px 3px rgb(27 28 25 / .04); }
        .stitch-eyebrow { color: var(--accent-dark); font-size: 11px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; }
        .stitch-button { background: var(--accent); color: #fff; border-radius: .5rem; font-weight: 700; transition: background .2s, transform .2s; }
        .stitch-button:hover { background: #c96e08; }
        .stitch-button:active { transform: scale(.98); }
    </style>
</head>
<body class="h-full bg-[#EFE9E3] text-slate-800 antialiased font-sans">

<div class="h-screen w-full flex overflow-hidden">

    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-40 hidden md:hidden" onclick="closeSidebar()"></div>

    <aside id="sidebar" class="
        fixed md:relative inset-y-0 left-0 z-50 md:z-auto
        w-64 md:w-64 h-full
        bg-[#211D1A] text-white
        flex flex-col flex-shrink-0
        border-r border-[#211D1A] shadow-md
        transform -translate-x-full md:translate-x-0
        transition-transform duration-300 ease-in-out
    ">
        <div class="flex flex-col h-full overflow-hidden">
            <div class="h-16 px-5 flex items-center justify-between border-b border-white/10 bg-[#211D1A] flex-shrink-0">
                <a href="{{ route('login') }}" class="flex items-center space-x-3 group min-w-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Aressa Golden Farm" class="h-10 w-auto max-w-[44px] object-contain filter drop-shadow-md group-hover:scale-105 transition-transform duration-300 flex-shrink-0">
                    <div class="flex flex-col min-w-0">
                        <span class="font-extrabold text-white tracking-tight text-sm leading-tight truncate">Aressa Golden Farm</span>
                        <span class="text-[10px] text-[#EFE9E3]/80 font-medium truncate">Presensi Karyawan</span>
                    </div>
                </a>
                <button onclick="closeSidebar()" class="md:hidden p-1 text-white/70 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto p-3 space-y-5 text-xs font-medium">
                @auth
                    @if(in_array(auth()->user()->role, ['super_admin', 'admin', 'hr_admin', 'manager']))
                        <div>
                            <div class="px-3 pb-1.5 text-[10px] font-bold text-[#EFE9E3]/70 uppercase tracking-widest">Utama</div>
                            <a href="{{ route('dashboard.admin') }}" onclick="closeSidebar()" class="flex items-center px-3 py-2 rounded-xl transition-all {{ request()->routeIs('dashboard.admin') ? 'bg-[#EFE9E3] text-[#3A3027] font-bold shadow-sm' : 'hover:bg-[#3A3027]/40 text-white' }}">
                                <svg class="w-4 h-4 mr-2.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                Dashboard Executive
                            </a>
                        </div>
                    @endif

                    @if(auth()->user()->isEmployee())
                        <div>
                            <div class="px-3 pb-1.5 text-[10px] font-bold text-[#EFE9E3]/70 uppercase tracking-widest">Presensi Saya</div>
                            <div class="space-y-0.5">
                                <a href="{{ route('dashboard.employee') }}" onclick="closeSidebar()" class="flex items-center px-3 py-2 rounded-xl transition-all {{ request()->routeIs('dashboard.employee') ? 'bg-[#EFE9E3] text-[#3A3027] font-bold shadow-sm' : 'hover:bg-[#3A3027]/40 text-white' }}">
                                    <svg class="w-4 h-4 mr-2.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Clock In / Clock Out
                                </a>
                                <a href="{{ route('attendance.history') }}" onclick="closeSidebar()" class="flex items-center px-3 py-2 rounded-xl transition-all {{ request()->routeIs('attendance.history') ? 'bg-[#EFE9E3] text-[#3A3027] font-bold shadow-sm' : 'hover:bg-[#3A3027]/40 text-white' }}">
                                    <svg class="w-4 h-4 mr-2.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    Riwayat Presensi
                                </a>
                            </div>
                        </div>
                    @endif

                    <div>
                        <div class="px-3 pb-1.5 text-[10px] font-bold text-[#EFE9E3]/70 uppercase tracking-widest">Pengajuan</div>
                        <div class="space-y-0.5">
                            <a href="{{ route('permissions.index') }}" onclick="closeSidebar()" class="flex items-center px-3 py-2 rounded-xl transition-all {{ request()->routeIs('permissions.*') ? 'bg-[#EFE9E3] text-[#3A3027] font-bold shadow-sm' : 'hover:bg-[#3A3027]/40 text-white' }}">
                                <svg class="w-4 h-4 mr-2.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Izin Presensi
                            </a>
                            <a href="{{ route('leaves.index') }}" onclick="closeSidebar()" class="flex items-center px-3 py-2 rounded-xl transition-all {{ request()->routeIs('leaves.*') ? 'bg-[#EFE9E3] text-[#3A3027] font-bold shadow-sm' : 'hover:bg-[#3A3027]/40 text-white' }}">
                                <svg class="w-4 h-4 mr-2.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Cuti Karyawan
                            </a>
                            <a href="{{ route('corrections.index') }}" onclick="closeSidebar()" class="flex items-center px-3 py-2 rounded-xl transition-all {{ request()->routeIs('corrections.*') ? 'bg-[#EFE9E3] text-[#3A3027] font-bold shadow-sm' : 'hover:bg-[#3A3027]/40 text-white' }}">
                                <svg class="w-4 h-4 mr-2.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Koreksi Presensi
                            </a>
                        </div>
                    </div>

                    @if(in_array(auth()->user()->role, ['super_admin', 'admin', 'hr_admin', 'manager']))
                        <div>
                            <div class="px-3 pb-1.5 text-[10px] font-bold text-[#EFE9E3]/70 uppercase tracking-widest">Laporan & Tim</div>
                            <div class="space-y-0.5">
                                <a href="{{ route('attendance.monitoring') }}" onclick="closeSidebar()" class="flex items-center px-3 py-2 rounded-xl transition-all {{ request()->routeIs('attendance.monitoring') ? 'bg-[#EFE9E3] text-[#3A3027] font-bold shadow-sm' : 'hover:bg-[#3A3027]/40 text-white' }}">
                                    <svg class="w-4 h-4 mr-2.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Monitoring Tim
                                </a>
                                <a href="{{ route('reports.index') }}" onclick="closeSidebar()" class="flex items-center px-3 py-2 rounded-xl transition-all {{ request()->routeIs('reports.*') ? 'bg-[#EFE9E3] text-[#3A3027] font-bold shadow-sm' : 'hover:bg-[#3A3027]/40 text-white' }}">
                                    <svg class="w-4 h-4 mr-2.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Laporan & Export
                                </a>
                            </div>
                        </div>
                    @endif

                    @if(in_array(auth()->user()->role, ['super_admin', 'admin', 'hr_admin']))
                        <div>
                            <div class="px-3 pb-1.5 text-[10px] font-bold text-[#EFE9E3]/70 uppercase tracking-widest">Master Data</div>
                            <div class="space-y-0.5">
                                <a href="{{ route('employees.index') }}" onclick="closeSidebar()" class="flex items-center px-3 py-2 rounded-xl transition-all {{ request()->routeIs('employees.*') ? 'bg-[#EFE9E3] text-[#3A3027] font-bold shadow-sm' : 'hover:bg-[#3A3027]/40 text-white' }}">
                                    <svg class="w-4 h-4 mr-2.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    Data Karyawan
                                </a>
                                <a href="{{ route('departments.index') }}" onclick="closeSidebar()" class="flex items-center px-3 py-2 rounded-xl transition-all {{ request()->routeIs('departments.*') ? 'bg-[#EFE9E3] text-[#3A3027] font-bold shadow-sm' : 'hover:bg-[#3A3027]/40 text-white' }}">
                                    <svg class="w-4 h-4 mr-2.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    Departemen
                                </a>
                                <a href="{{ route('positions.index') }}" onclick="closeSidebar()" class="flex items-center px-3 py-2 rounded-xl transition-all {{ request()->routeIs('positions.*') ? 'bg-[#EFE9E3] text-[#3A3027] font-bold shadow-sm' : 'hover:bg-[#3A3027]/40 text-white' }}">
                                    <svg class="w-4 h-4 mr-2.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    Jabatan
                                </a>
                                <a href="{{ route('shifts.index') }}" onclick="closeSidebar()" class="flex items-center px-3 py-2 rounded-xl transition-all {{ request()->routeIs('shifts.*') ? 'bg-[#EFE9E3] text-[#3A3027] font-bold shadow-sm' : 'hover:bg-[#3A3027]/40 text-white' }}">
                                    <svg class="w-4 h-4 mr-2.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Shift Kerja
                                </a>
                                <a href="{{ route('schedules.index') }}" onclick="closeSidebar()" class="flex items-center px-3 py-2 rounded-xl transition-all {{ request()->routeIs('schedules.*') ? 'bg-[#EFE9E3] text-[#3A3027] font-bold shadow-sm' : 'hover:bg-[#3A3027]/40 text-white' }}">
                                    <svg class="w-4 h-4 mr-2.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Jadwal Kerja
                                </a>
                            </div>
                        </div>
                    @endif
                @endauth
            </nav>

            @auth
                <div class="p-3 border-t border-white/10 bg-[#211D1A] flex items-center justify-between flex-shrink-0">
                    <div class="flex items-center space-x-2.5 min-w-0 flex-1 mr-2">
                        <div class="w-8 h-8 rounded-full bg-[#EFE9E3] text-[#3A3027] font-bold flex items-center justify-center flex-shrink-0 text-xs">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-xs font-bold text-white truncate leading-tight">{{ auth()->user()->name }}</div>
                            <div class="text-[10px] text-[#EFE9E3]/80 capitalize truncate font-medium">{{ str_replace('_', ' ', auth()->user()->role) }}</div>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="flex-shrink-0">
                        @csrf
                        <button type="submit" title="Logout" class="p-1.5 text-[#EFE9E3] hover:text-white transition hover:bg-black/20 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-y-auto bg-[#F7F5F1]">

        <header class="h-16 bg-white border-b border-[#D9CFC7] px-4 sm:px-6 flex items-center justify-between sticky top-0 z-20 flex-shrink-0 shadow-xs">
            <div class="flex items-center gap-3">
                <button onclick="openSidebar()" class="md:hidden p-2 rounded-xl text-[#3A3027] hover:bg-[#EFE9E3] transition -ml-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="font-bold text-[#3A3027] text-sm sm:text-base tracking-tight truncate">
                    @yield('header_title', 'Dashboard')
                </h1>
            </div>

            <div class="flex items-center space-x-2 sm:space-x-3">
                <div class="hidden sm:flex items-center space-x-2 bg-[#EFE9E3] px-3 py-1.5 rounded-lg border border-[#D9CFC7] text-xs text-[#3A3027] font-medium">
                    <span class="w-2 h-2 rounded-full bg-[#C9B59C] animate-pulse"></span>
                    <span class="font-mono font-bold text-[#3A3027]" id="header-time">{{ \Carbon\Carbon::now()->format('d M Y, H:i') }} WIB</span>
                </div>
                @auth
                    <span class="hidden sm:inline-flex text-[11px] font-semibold px-2.5 py-1 bg-[#D9CFC7]/50 text-[#3A3027] rounded-md border border-[#D9CFC7] capitalize">
                        {{ str_replace('_', ' ', auth()->user()->role) }}
                    </span>
                @endauth
            </div>
        </header>

        <div class="px-4 sm:px-6 pt-5 space-y-3 flex-shrink-0">
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-3 rounded-xl flex items-center text-xs font-medium">
                    <svg class="w-4 h-4 mr-2.5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-900 px-4 py-3 rounded-xl flex items-center text-xs font-medium">
                    <svg class="w-4 h-4 mr-2.5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <main class="flex-1 p-4 sm:p-6 md:p-8 pb-12">
            @yield('content')
        </main>

        <footer class="bg-white border-t border-[#D9CFC7] py-3.5 px-6 text-center text-xs text-[#C9B59C] font-semibold flex-shrink-0">
            Perusahaan Aressa Golden Farm &copy; 2026 Enterprise Attendance System
        </footer>
    </div>
</div>

<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.remove('-translate-x-full');
        document.getElementById('sidebar-overlay').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.add('-translate-x-full');
        document.getElementById('sidebar-overlay').classList.add('hidden');
        document.body.style.overflow = '';
    }
    function updateHeaderClock() {
        const now = new Date();
        const day = String(now.getDate()).padStart(2, '0');
        const monthNames = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];
        const month = monthNames[now.getMonth()];
        const year = now.getFullYear();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        const el = document.getElementById('header-time');
        if (el) el.textContent = `${day} ${month} ${year}, ${h}:${m}:${s} WIB`;
    }
    setInterval(updateHeaderClock, 1000);
</script>
</body>
</html>
