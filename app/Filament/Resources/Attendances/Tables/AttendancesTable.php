<?php

namespace App\Filament\Resources\Attendances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('employee.name')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->employee?->employee_number),
                TextColumn::make('employee.department.name')
                    ->label('Departemen')
                    ->badge()
                    ->color('gray')
                    ->toggleable(),
                TextColumn::make('clock_in')
                    ->label('Masuk')
                    ->time('H:i')
                    ->fontFamily('mono')
                    ->placeholder('-'),
                TextColumn::make('clock_out')
                    ->label('Pulang')
                    ->time('H:i')
                    ->fontFamily('mono')
                    ->placeholder('-'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Present' => 'success',
                        'Late' => 'warning',
                        'Leave' => 'purple',
                        'Permission' => 'info',
                        'Absent' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'Present' => 'Hadir',
                        'Late' => 'Terlambat',
                        'Leave' => 'Cuti',
                        'Permission' => 'Izin',
                        'Absent' => 'Alfa',
                        default => $state,
                    })
                    ->sortable(),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Present' => 'Hadir',
                        'Late' => 'Terlambat',
                        'Leave' => 'Cuti',
                        'Permission' => 'Izin',
                        'Absent' => 'Alfa',
                    ]),
                SelectFilter::make('employee_id')
                    ->relationship('employee', 'name')
                    ->label('Karyawan')
                    ->searchable()
                    ->preload(),
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
