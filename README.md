# Aressa Golden Farm — Employee Attendance & HR Management System

Sistem informasi presensi dan manajemen sumber daya manusia (SDM) berbasis web untuk **Aressa Golden Farm**, perusahaan yang diproyeksikan bergerak di bidang produksi dan ekspor gula aren. Aplikasi ini dilengkapi arsitektur antarmuka ganda (*dual-interface*): portal karyawan berbasis Blade & Tailwind CSS yang responsif serta portal eksekutif dan HR berbasis Filament Admin Panel.

---

## 🚀 Fitur Utama

### 1. Portal Karyawan (Self-Service)
- **Pencatatan Presensi Real-Time:** Clock In & Clock Out harian dengan pencegahan absensi ganda.
- **Kalkulasi Keterlambatan Otomatis:** Perhitungan status *Present* / *Late* berdasarkan shift kerja dan toleransi menit.
- **Riwayat Kehadiran Pribadi:** Filter presensi berdasarkan tanggal, bulan, dan status kehadiran.
- **Pengajuan Izin & Cuti:** Formulir pengajuan izin/cuti mandiri dengan pemantauan status persetujuan.
- **Koreksi Absensi:** Pengajuan koreksi waktu presensi jika terjadi kendala teknis saat absensi.

### 2. Portal Administrator & HR (Filament Panel `/admin`)
- **Master Data Karyawan:** Pendataan profil lengkap, NIK, kontak, gender, departemen, dan jabatan.
- **Departemen & Jabatan:** Pengelolaan struktur organisasi perusahaan.
- **Manajemen Shift & Jadwal:** Konfigurasi jam shift (Pagi, Siang, Malam), toleransi keterlambatan, dan hari kerja.
- **Persetujuan (Approval Workflow):** Verifikasi persetujuan atau penolakan pengajuan izin, cuti, dan koreksi absensi.
- **Hak Akses & Pengguna:** Role-based access control (*Super Admin*, *HR Admin*, *Manager*, *Employee*).
- **Struktur Operasional Ekspor:** Data awal mencakup Produksi & Pengolahan, SDM & Umum, Keuangan & Ekspor, Quality Control, serta Gudang & Logistik.

---

## 🛠️ Tech Stack

- **Backend Framework:** [Laravel 12](https://laravel.com) (PHP 8.2+)
- **Admin Panel:** [Filament v5](https://filamentphp.com)
- **Database:** MySQL
- **Frontend Styling:** Tailwind CSS & Blade Templating
- **Design Palette:** Nordic Warm Sand (`#F9F8F6`, `#EFE9E3`, `#D9CFC7`, `#C9B59C`)

---

## 💻 Panduan Instalasi Lokal

1. **Clone repositori:**
   ```bash
   git clone https://github.com/WilsonHutagalung/Sistem-Presensi-Manajemen-SDM.git
   cd Sistem-Presensi-Manajemen-SDM
   ```

2. **Install dependensi PHP & JavaScript:**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment:**
   Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Pastikan konfigurasi database di `.env` sesuai dengan MySQL lokal Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=presensi
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Migrasi Database & Data Awal (Seeder):**
   ```bash
   php artisan migrate --seed
   php artisan storage:link
   ```

5. **Jalankan Aplikasi:**
   ```bash
   php artisan serve
   ```
   Akses portal karyawan di: `http://127.0.0.1:8000`  
   Akses panel admin Filament di: `http://127.0.0.1:8000/admin`

---

## 🔑 Akun Demo (Default Seeder)

| Role | Email | Password | Akses |
|---|---|---|---|
| **Super Admin** | `superadmin@example.com` | `password` | Panel Admin (`/admin`) |
| **HR Admin** | `admin@example.com` | `password` | Panel Admin (`/admin`) |
| **Manager** | `manager@example.com` | `password` | Dashboard Operasional |
| **Employee** | `budisantoso@gmail.com` | `password` | Portal Presensi |
| **IT Staff** | `wilson@gmail.com` | `password` | Portal Presensi |

---

## 📄 Lisensi
Hak Cipta © 2026 Perusahaan Aressa Golden Farm. Dikembangkan untuk portofolio dan manajemen operasional internal.
