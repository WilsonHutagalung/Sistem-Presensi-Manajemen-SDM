<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Akun — Aressa Golden Farm</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <style>
        :root {
            --background: #fbf9f4;
            --surface: #ffffff;
            --surface-soft: #f5f3ee;
            --ink: #1b1c19;
            --muted: #746d67;
            --outline: #d0c4bd;
            --primary-dark: #241e19;
            --orange: #fe932c;
            --orange-dark: #783e00;
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--background); color: var(--ink); }
        .material-icon { font-family: Arial, sans-serif; font-size: 19px; line-height: 1; }
        .login-input { background: var(--background); border: 1px solid rgb(208 196 189 / .6); }
        .login-input:focus { background: #fff; border-color: var(--orange); box-shadow: 0 0 0 3px rgb(254 147 44 / .15); }
    </style>
</head>
<body class="min-h-full flex flex-col antialiased">
    <header class="w-full bg-white border-b border-[#e4e2dd] px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <img src="{{ asset('images/logo.png') }}" alt="" class="w-8 h-8 object-contain">
            <span class="font-bold tracking-tight text-sm sm:text-base">Aressa Golden Farm</span>
        </div>
        <div class="flex items-center gap-2 text-xs text-[#746d67]">
            <span class="hidden md:inline-flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Portal Kehadiran Resmi
            </span>
            <span class="hidden md:block h-4 w-px bg-[#e4e2dd]"></span>
            <span>IDN (WIB)</span>
        </div>
    </header>

    <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8 flex items-center justify-center">
        <div class="w-full grid grid-cols-1 lg:grid-cols-12 rounded-xl bg-white border border-[#d0c4bd]/40 shadow-sm overflow-hidden">
            <section class="lg:col-span-5 min-h-[530px] bg-[#241e19] text-white p-6 sm:p-8 lg:p-10 relative overflow-hidden flex flex-col justify-between">
                <div class="absolute inset-0 opacity-20 pointer-events-none" style="background: radial-gradient(circle at 30% 20%, #fe932c 0, transparent 35%), radial-gradient(circle at 90% 80%, #904d00 0, transparent 42%);"></div>
                <div class="relative z-10 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-black/35 border border-white/20 text-[#ffdcc3] text-[10px] font-bold tracking-wider uppercase">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#85f8c4]"></span>
                        Portal Terpadu Kepegawaian
                    </div>
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo.png') }}" alt="" class="w-14 h-14 object-contain">
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold tracking-tight leading-tight">Aressa Golden Farm</h1>
                            <p class="text-xs text-[#ffdcc3] mt-1">Smart Agro-Industry & Workforce Management</p>
                        </div>
                    </div>
                    <p class="text-base text-white/95 leading-relaxed font-semibold border-l-2 border-[#ffb77d] pl-3.5">
                        Mewujudkan Integritas, Produktivitas, dan Keunggulan Operasional Berkelanjutan.
                    </p>
                    <div class="space-y-2.5">
                        <div class="p-3 rounded-lg bg-black/30 border border-white/15 flex items-start gap-2.5">
                            <span class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-[#ffdcc3] shrink-0">◎</span>
                            <div><p class="text-xs font-bold">Geofencing Presisi</p><p class="text-xs text-white/70 leading-snug mt-0.5">Validasi radius satelit real-time di seluruh estate dan kantor pusat.</p></div>
                        </div>
                        <div class="p-3 rounded-lg bg-black/30 border border-white/15 flex items-start gap-2.5">
                            <span class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-[#ffdcc3] shrink-0">↔</span>
                            <div><p class="text-xs font-bold">Sinkronisasi Payroll Otomatis</p><p class="text-xs text-white/70 leading-snug mt-0.5">Jam kerja, lembur, dan potongan terhubung langsung ke HRIS.</p></div>
                        </div>
                        <div class="p-3 rounded-lg bg-black/30 border border-white/15 flex items-start gap-2.5">
                            <span class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-[#85f8c4] shrink-0">✓</span>
                            <div><p class="text-xs font-bold">Integritas & Audit 100%</p><p class="text-xs text-white/70 leading-snug mt-0.5">Data kehadiran terlindungi untuk transparansi penuh kepegawaian.</p></div>
                        </div>
                    </div>
                </div>
                <div class="relative z-10 pt-5 border-t border-white/15">
                    <div class="flex items-center justify-between gap-2 mb-1.5">
                        <span class="text-[11px] uppercase font-bold text-[#85f8c4] tracking-wider">Operasional Terintegrasi</span>
                        <span class="text-[11px] text-white/60">Aressa Agro Export</span>
                    </div>
                    <p class="text-xs text-white/70">Mendukung rantai pasok, pengolahan, dan ekspor gula aren berkualitas dari Indonesia.</p>
                </div>
            </section>

            <section class="lg:col-span-7 p-6 sm:p-10 lg:p-14 flex flex-col justify-center">
                <div class="max-w-md w-full mx-auto">
                    <div class="mb-7">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-2 h-2 rounded-full bg-[#904d00]"></span>
                            <span class="text-[11px] text-[#904d00] tracking-widest uppercase font-bold">Autentikasi Pegawai</span>
                        </div>
                        <h2 class="text-3xl font-bold tracking-tight">Masuk ke Sistem</h2>
                        <p class="text-sm text-[#4d4540] mt-1.5 leading-relaxed">Silakan masukkan NIK atau alamat email kantor yang telah terdaftar pada sistem presensi.</p>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 bg-emerald-50 text-emerald-900 p-3 rounded-lg text-xs font-semibold border border-emerald-200">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="mb-4 bg-rose-50 text-rose-900 p-3 rounded-lg text-xs font-semibold border border-rose-200">
                            <ul class="list-disc pl-4 space-y-0.5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="email" class="block text-xs text-[#1b1c19] mb-1.5 font-semibold">ALAMAT EMAIL / NIK KARYAWAN</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-[#746d67] pointer-events-none">@</span>
                                <input type="text" name="email" id="email" value="{{ old('email') }}" required autofocus
                                    placeholder="contoh: nama@aressagolden.farm atau NIK"
                                    class="login-input block w-full pl-10 pr-4 py-2.5 rounded-lg text-sm outline-none transition">
                            </div>
                            <p class="mt-1 text-[11px] text-[#746d67]">Gunakan NIK atau email resmi korporat.</p>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password" class="block text-xs text-[#1b1c19] font-semibold">KATA SANDI</label>
                                <span class="text-xs text-[#904d00] font-medium">Akun terdaftar</span>
                            </div>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-[#746d67] pointer-events-none">●</span>
                                <input type="password" name="password" id="password" required placeholder="Masukkan kata sandi akun"
                                    class="login-input block w-full pl-10 pr-11 py-2.5 rounded-lg text-sm outline-none transition">
                                <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#746d67] hover:text-[#1b1c19]" aria-controls="password" aria-label="Tampilkan atau sembunyikan kata sandi">
                                    <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.036 12.322a1.012 1.012 0 0 1 0-.644C3.423 7.51 7.36 4.5 12 4.5c4.64 0 8.577 3.01 9.964 7.178.07.209.07.433 0 .644C20.577 16.49 16.64 19.5 12 19.5c-4.64 0-8.577-3.01-9.964-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center gap-2 cursor-pointer select-none text-sm">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded text-[#904d00] border-[#d0c4bd] focus:ring-[#fe932c]/30">
                                Ingat Saya di Perangkat Ini
                            </label>
                            <span class="hidden sm:inline-flex items-center gap-1 text-xs text-[#746d67]">Sesi 30 Hari</span>
                        </div>
                        <button type="submit" class="w-full py-3 px-4 rounded-lg bg-[#904d00] hover:bg-[#783e00] active:scale-[.99] text-white text-sm font-bold shadow-md transition flex items-center justify-center gap-2">
                            <span>Masuk Akun</span><span class="text-lg leading-none">→</span>
                        </button>
                        <a href="{{ route('password.request') }}" class="block text-center text-xs font-semibold text-[#904d00] hover:underline">Lupa password?</a>
                    </form>

                    <div class="mt-7 pt-4 border-t border-[#e4e2dd] flex items-center justify-between gap-3 text-xs text-[#746d67]">
                        <span>Kendala login? Hubungi HRD/IT Support</span>
                        <a class="font-semibold text-[#904d00] hover:underline whitespace-nowrap" href="mailto:it-support@aressagolden.farm">Bantuan Akun</a>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <footer class="w-full min-h-[56px] border-t border-[#e4e2dd] bg-white px-4 sm:px-6 lg:px-8 flex items-center justify-center text-center">
        <div class="w-full text-xs text-[#746d67]">
            <span>© 2026 PT Aressa Golden Farm. Seluruh Hak Cipta Dilindungi.</span>
        </div>
    </footer>

    <script>
        document.getElementById('toggle-password').addEventListener('click', function () {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const isVisible = password.type === 'text';
            password.type = isVisible ? 'password' : 'text';
            this.setAttribute('aria-label', isVisible ? 'Tampilkan atau sembunyikan kata sandi' : 'Sembunyikan atau tampilkan kata sandi');
            eyeIcon.innerHTML = isVisible
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.036 12.322a1.012 1.012 0 0 1 0-.644C3.423 7.51 7.36 4.5 12 4.5c4.64 0 8.577 3.01 9.964 7.178.07.209.07.433 0 0.644C20.577 16.49 16.64 19.5 12 19.5c-4.64 0-8.577-3.01-9.964-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m3 3 18 18M10.58 10.58a2 2 0 0 0 2.83 2.83M9.88 5.09A10.4 10.4 0 0 1 12 4.5c4.64 0 8.58 3.01 9.96 7.18a10.5 10.5 0 0 1-2.18 3.63M6.23 6.23A10.5 10.5 0 0 0 2.04 12.32C3.42 16.49 7.36 19.5 12 19.5c1.01 0 1.99-.14 2.91-.42"/>';
        });
    </script>
</body>
</html>
