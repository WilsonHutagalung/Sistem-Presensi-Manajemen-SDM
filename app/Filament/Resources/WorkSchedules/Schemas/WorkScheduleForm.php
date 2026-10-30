<?php

namespace App\Filament\Resources\WorkSchedules\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class WorkScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('day_of_week')
                    ->label('Nama Hari')
                    ->options([
                        'Monday' => 'Senin (Monday)',
                        'Tuesday' => 'Selasa (Tuesday)',
                        'Wednesday' => 'Rabu (Wednesday)',
                        'Thursday' => 'Kamis (Thursday)',
                        'Friday' => 'Jumat (Friday)',
                        'Saturday' => 'Sabtu (Saturday)',
                        'Sunday' => 'Minggu (Sunday)',
                    ])
                    ->required(),
                Toggle::make('is_working_day')
                    ->label('Hari Kerja Aktif')
                    ->helperText('Jika diaktifkan, presensi wajib dilakukan pada hari ini')
                    ->default(true),
            ]);
    }
}
