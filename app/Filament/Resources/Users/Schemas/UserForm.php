<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                Select::make('role')
                    ->label('Peran / Hak Akses (Role)')
                    ->options(fn (): array => Auth::user()?->isSuperAdmin()
                        ? [
                            'super_admin' => 'Super Administrator (Full Akses)',
                            'admin' => 'Admin HR / Operasional',
                            'hr_admin' => 'HR Staff',
                            'manager' => 'Manager Departemen',
                            'employee' => 'Karyawan (Akses Portal Presensi)',
                        ]
                        : [
                            'manager' => 'Manager Departemen',
                            'employee' => 'Karyawan (Akses Portal Presensi)',
                        ])
                    ->default('employee')
                    ->required(),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->helperText('Kosongkan jika tidak ingin mengubah password saat edit.'),
            ]);
    }
}
