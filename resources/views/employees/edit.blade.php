@extends('layouts.app')

@section('title', 'Edit Karyawan — Perusahaan Aressa Golden Farm')
@section('header_title', 'Edit Data Karyawan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-[#D9CFC7] shadow-sm">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-[#D9CFC7]">
            <h2 class="text-sm font-bold text-[#3A3027]">Edit Data: {{ $employee->name }}</h2>
            <a href="{{ route('employees.show', $employee) }}" class="text-xs font-bold text-[#C9B59C] hover:text-[#3A3027] transition">&larr; Kembali</a>
        </div>

        @if($errors->any())
            <div class="mb-5 p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-xs">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('employees.update', $employee) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-[#3A3027] mb-1.5">Nomor Karyawan (NIK) *</label>
                    <input type="text" name="employee_number" value="{{ old('employee_number', $employee->employee_number) }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#D9CFC7] focus:ring-2 focus:ring-[#C9B59C]/40 focus:border-[#C9B59C] outline-none text-xs font-mono bg-[#EFE9E3]/30 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#3A3027] mb-1.5">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name', $employee->name) }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#D9CFC7] focus:ring-2 focus:ring-[#C9B59C]/40 focus:border-[#C9B59C] outline-none text-xs bg-[#EFE9E3]/30 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#3A3027] mb-1.5">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $employee->user->email ?? '') }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#D9CFC7] focus:ring-2 focus:ring-[#C9B59C]/40 focus:border-[#C9B59C] outline-none text-xs bg-[#EFE9E3]/30 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#3A3027] mb-1.5">Password Baru <span class="font-normal text-slate-500">(Kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#D9CFC7] focus:ring-2 focus:ring-[#C9B59C]/40 focus:border-[#C9B59C] outline-none text-xs bg-[#EFE9E3]/30 transition" placeholder="••••••••">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#3A3027] mb-1.5">Role Sistem *</label>
                    <select name="role" required class="w-full px-3.5 py-2.5 rounded-xl border border-[#D9CFC7] focus:ring-2 focus:ring-[#C9B59C]/40 focus:border-[#C9B59C] outline-none text-xs bg-white transition">
                        <option value="employee" {{ old('role', $employee->user->role ?? '') == 'employee' ? 'selected' : '' }}>Employee</option>
                        <option value="manager" {{ old('role', $employee->user->role ?? '') == 'manager' ? 'selected' : '' }}>Manager</option>
                        @if(auth()->user()->isSuperAdmin())
                            <option value="admin" {{ old('role', $employee->user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="hr_admin" {{ old('role', $employee->user->role ?? '') == 'hr_admin' ? 'selected' : '' }}>HR Admin</option>
                            <option value="super_admin" {{ old('role', $employee->user->role ?? '') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#3A3027] mb-1.5">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#D9CFC7] focus:ring-2 focus:ring-[#C9B59C]/40 focus:border-[#C9B59C] outline-none text-xs bg-[#EFE9E3]/30 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#3A3027] mb-1.5">Jenis Kelamin *</label>
                    <select name="gender" required class="w-full px-3.5 py-2.5 rounded-xl border border-[#D9CFC7] focus:ring-2 focus:ring-[#C9B59C]/40 focus:border-[#C9B59C] outline-none text-xs bg-white transition">
                        <option value="L" {{ old('gender', $employee->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender', $employee->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#3A3027] mb-1.5">Tanggal Lahir</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $employee->birth_date ? $employee->birth_date->format('Y-m-d') : '') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#D9CFC7] focus:ring-2 focus:ring-[#C9B59C]/40 focus:border-[#C9B59C] outline-none text-xs bg-[#EFE9E3]/30 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#3A3027] mb-1.5">Departemen *</label>
                    <select name="department_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-[#D9CFC7] focus:ring-2 focus:ring-[#C9B59C]/40 focus:border-[#C9B59C] outline-none text-xs bg-white transition">
                        <option value="">-- Pilih Departemen --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#3A3027] mb-1.5">Jabatan *</label>
                    <select name="position_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-[#D9CFC7] focus:ring-2 focus:ring-[#C9B59C]/40 focus:border-[#C9B59C] outline-none text-xs bg-white transition">
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach($positions as $pos)
                            <option value="{{ $pos->id }}" {{ old('position_id', $employee->position_id) == $pos->id ? 'selected' : '' }}>{{ $pos->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#3A3027] mb-1.5">Tanggal Bergabung *</label>
                    <input type="date" name="join_date" value="{{ old('join_date', $employee->join_date ? $employee->join_date->format('Y-m-d') : '') }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#D9CFC7] focus:ring-2 focus:ring-[#C9B59C]/40 focus:border-[#C9B59C] outline-none text-xs bg-[#EFE9E3]/30 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#3A3027] mb-1.5">Status Karyawan *</label>
                    <select name="status" required class="w-full px-3.5 py-2.5 rounded-xl border border-[#D9CFC7] focus:ring-2 focus:ring-[#C9B59C]/40 focus:border-[#C9B59C] outline-none text-xs bg-white transition">
                        <option value="active" {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $employee->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A3027] mb-1.5">Alamat Lengkap</label>
                <textarea name="address" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-[#D9CFC7] focus:ring-2 focus:ring-[#C9B59C]/40 focus:border-[#C9B59C] outline-none text-xs bg-[#EFE9E3]/30 transition resize-none">{{ old('address', $employee->address) }}</textarea>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2.5 pt-4 border-t border-[#D9CFC7]">
                <a href="{{ route('employees.index') }}" class="px-5 py-2.5 rounded-xl border border-[#D9CFC7] text-[#3A3027] hover:bg-[#EFE9E3] font-semibold text-xs text-center transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-[#C9B59C] hover:bg-[#3A3027] text-white font-bold rounded-xl text-xs shadow-xs transition">Perbarui Data</button>
            </div>
        </form>
    </div>
</div>
@endsection
