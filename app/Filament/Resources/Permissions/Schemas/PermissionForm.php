<?php

namespace App\Filament\Resources\Permissions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class PermissionForm
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
                    ->label('Tanggal Izin')
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->default(now())
                    ->required(),
                Textarea::make('reason')
                    ->label('Alasan Izin')
                    ->rows(3)
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('attachment')
                    ->label('Lampiran Bukti (Surat Dokter / Dokumen)')
                    ->directory('permissions')
                    ->disk('public')
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
