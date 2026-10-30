<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Akun & Identitas')
                    ->description('Data akun login dan nomor induk karyawan')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('user_id')
                                ->relationship('user', 'email')
                                ->label('Akun Pengguna (Email)')
                                ->searchable()
                                ->preload()
                                ->required(),
                            TextInput::make('employee_number')
                                ->label('Nomor Induk Karyawan (NIP/NIK)')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->placeholder('Contoh: EMP-001'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Lengkap')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('phone')
                                ->label('Nomor Telepon / WhatsApp')
                                ->tel()
                                ->placeholder('081234567890'),
                        ]),
                        Grid::make(2)->schema([
                            Radio::make('gender')
                                ->label('Jenis Kelamin')
                                ->options([
                                    'L' => 'Laki-laki',
                                    'P' => 'Perempuan',
                                ])
                                ->inline()
                                ->default('L'),
                            DatePicker::make('birth_date')
                                ->label('Tanggal Lahir')
                                ->native(false)
                                ->displayFormat('d/m/Y'),
                        ]),
                        Textarea::make('address')
                            ->label('Alamat Domisili')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Penempatan & Status')
                    ->description('Departemen, jabatan, dan status kerja karyawan')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('department_id')
                                ->relationship('department', 'name')
                                ->label('Departemen')
                                ->searchable()
                                ->preload()
                                ->nullable(),
                            Select::make('position_id')
                                ->relationship('position', 'name')
                                ->label('Jabatan')
                                ->searchable()
                                ->preload()
                                ->nullable(),
                        ]),
                        Grid::make(2)->schema([
                            DatePicker::make('join_date')
                                ->label('Tanggal Mulai Bekerja')
                                ->native(false)
                                ->displayFormat('d/m/Y')
                                ->default(now()),
                            Select::make('status')
                                ->label('Status Karyawan')
                                ->options([
                                    'active' => 'Aktif',
                                    'inactive' => 'Nonaktif',
                                ])
                                ->default('active')
                                ->required(),
                        ]),
                    ]),
            ]);
    }
}
