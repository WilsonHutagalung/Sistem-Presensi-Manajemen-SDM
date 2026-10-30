<?php

namespace App\Filament\Resources\Shifts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShiftsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Shift')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('start_time')
                    ->label('Jam Masuk')
                    ->time('H:i')
                    ->fontFamily('mono')
                    ->badge()
                    ->color('info'),
                TextColumn::make('end_time')
                    ->label('Jam Pulang')
                    ->time('H:i')
                    ->fontFamily('mono')
                    ->badge()
                    ->color('warning'),
                TextColumn::make('late_tolerance')
                    ->label('Toleransi Telat')
                    ->formatStateUsing(fn ($state) => "{$state} menit"),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Aktif',
                        'inactive' => 'Nonaktif',
                        default => $state,
                    }),
            ])
            ->filters([
                //
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
