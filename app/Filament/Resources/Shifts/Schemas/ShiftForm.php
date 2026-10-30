<?php

namespace App\Filament\Resources\Shifts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ShiftForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Shift')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Contoh: Shift Pagi, Shift Siang'),

                Grid::make(2)->schema([
                    TimePicker::make('start_time')
                        ->label('Jam Masuk')
                        ->required()
                        ->seconds(false),
                    TimePicker::make('end_time')
                        ->label('Jam Pulang')
                        ->required()
                        ->seconds(false),
                ]),

                Grid::make(2)->schema([
                    TextInput::make('late_tolerance')
                        ->label('Toleransi Keterlambatan (Menit)')
                        ->numeric()
                        ->default(15)
                        ->required()
                        ->suffix('Menit'),
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'active' => 'Aktif',
                            'inactive' => 'Nonaktif',
                        ])
                        ->default('active')
                        ->required(),
                ]),
            ]);
    }
}
