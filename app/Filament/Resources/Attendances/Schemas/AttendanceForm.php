<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class AttendanceForm
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
                DatePicker::make('date')
                    ->label('Tanggal')
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->default(now())
                    ->required(),
                Grid::make(2)->schema([
                    TimePicker::make('clock_in')
                        ->label('Jam Masuk (Clock In)')
                        ->seconds(false),
                    TimePicker::make('clock_out')
                        ->label('Jam Pulang (Clock Out)')
                        ->seconds(false),
                ]),
                Select::make('status')
                    ->label('Status Kehadiran')
                    ->options([
                        'Present' => 'Hadir (Present)',
                        'Late' => 'Terlambat (Late)',
                        'Leave' => 'Cuti (Leave)',
                        'Permission' => 'Izin (Permission)',
                        'Absent' => 'Alfa / Tidak Masuk (Absent)',
                    ])
                    ->default('Present')
                    ->required(),
            ]);
    }
}
