<?php

namespace App\Filament\Resources\AttendanceCorrections\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class AttendanceCorrectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->label('Karyawan')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('attendance_id')
                    ->relationship('attendance', 'date')
                    ->label('Tanggal Absensi yang Dikoreksi')
                    ->searchable()
                    ->preload(),
                Grid::make(2)->schema([
                    TimePicker::make('requested_clock_in')
                        ->label('Koreksi Jam Masuk')
                        ->seconds(false),
                    TimePicker::make('requested_clock_out')
                        ->label('Koreksi Jam Pulang')
                        ->seconds(false),
                ]),
                Textarea::make('reason')
                    ->label('Alasan Koreksi')
                    ->rows(3)
                    ->required()
                    ->columnSpanFull(),
                Grid::make(2)->schema([
                    Select::make('status')
                        ->label('Status Persetujuan')
                        ->options([
                            'Pending' => 'Menunggu (Pending)',
                            'Approved' => 'Disetujui (Approved)',
                            'Rejected' => 'Ditolak (Rejected)',
                        ])
                        ->default('Pending')
                        ->required(),
                    Select::make('reviewed_by')
                        ->relationship('reviewer', 'name')
                        ->label('Direview Oleh')
                        ->searchable()
                        ->preload(),
                ]),
                DateTimePicker::make('reviewed_at')
                    ->label('Waktu Diputuskan')
                    ->native(false)
                    ->displayFormat('d/m/Y H:i'),
            ]);
    }
}
