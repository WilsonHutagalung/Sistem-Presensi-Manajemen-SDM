<?php

namespace App\Filament\Resources\Positions\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PositionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Jabatan')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Misal: Software Engineer, HR Staff, Manager'),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(4)
                    ->columnSpanFull()
                    ->placeholder('Uraian tanggung jawab jabatan...'),
            ]);
    }
}
