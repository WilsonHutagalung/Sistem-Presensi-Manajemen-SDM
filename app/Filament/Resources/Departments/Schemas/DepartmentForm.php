<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Departemen')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Misal: IT, HR, Keuangan'),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(4)
                    ->columnSpanFull()
                    ->placeholder('Keterangan tugas dan fungsi departemen...'),
            ]);
    }
}
