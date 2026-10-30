<?php

namespace App\Filament\Resources\Permissions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PermissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label('Tanggal Izin')
                    ->date('d M Y')
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('employee.name')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->employee?->department?->name),
                TextColumn::make('reason')
                    ->label('Alasan')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->reason),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        'Pending' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'Approved' => 'Disetujui',
                        'Rejected' => 'Ditolak',
                        'Pending' => 'Menunggu',
                        default => $state,
                    })
                    ->sortable(),
                TextColumn::make('approver.name')
                    ->label('Diproses Oleh')
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Diajukan')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Pending' => 'Menunggu',
                        'Approved' => 'Disetujui',
                        'Rejected' => 'Ditolak',
                    ]),
                SelectFilter::make('employee_id')
                    ->relationship('employee', 'name')
                    ->label('Karyawan')
                    ->searchable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
