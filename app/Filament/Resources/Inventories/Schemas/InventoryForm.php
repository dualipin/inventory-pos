<?php

namespace App\Filament\Resources\Inventories\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InventoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->label('Producto')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload()
                    ->disabledOn('edit')
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('stock')
                    ->label('Existencias actuales')
                    ->numeric()
                    ->disabled()
                    ->helperText('Las existencias se actualizan desde los movimientos de inventario.'),
                DateTimePicker::make('last_counted_at')
                    ->label('Último conteo')
                    ->helperText('Fecha del último conteo físico realizado.'),
            ]);
    }
}
