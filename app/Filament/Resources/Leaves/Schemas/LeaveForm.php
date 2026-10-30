<?php

namespace App\Filament\Resources\Leaves\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class LeaveForm
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
                Grid::make(2)->schema([
                    DatePicker::make('start_date')
                        ->label('Tanggal Mulai')
                        ->native(false)
                        ->displayFormat('d/m/Y')
                        ->required(),
                    DatePicker::make('end_date')
                        ->label('Tanggal Selesai')
                        ->native(false)
                        ->displayFormat('d/m/Y')
                        ->required(),
                ]),
                Textarea::make('reason')
                    ->label('Alasan Cuti')
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
                    Select::make('approved_by')
                        ->relationship('approver', 'name')
                        ->label('Disetujui Oleh')
                        ->searchable()
                        ->preload(),
                ]),
                DateTimePicker::make('approved_at')
                    ->label('Waktu Diputuskan')
                    ->native(false)
                    ->displayFormat('d/m/Y H:i'),
            ]);
    }
}
