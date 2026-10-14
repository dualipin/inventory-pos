<?php

namespace App\Filament\Resources\InventoryMovements\Schemas;

use App\Enums\MovementType;
use App\Models\Inventory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InventoryMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('inventory_id')
                    ->label('Producto')
                    ->options(fn (): array => Inventory::query()
                        ->with('product:id,name')
                        ->get()
                        ->mapWithKeys(fn (Inventory $inventory): array => [
                            $inventory->id => sprintf('%s (inventario #%d)', $inventory->product?->name, $inventory->id),
                        ])
                        ->all())
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('type')
                    ->label('Tipo de movimiento')
                    ->options(MovementType::class)
                    ->default(MovementType::Purchase)
                    ->required()
                    ->helperText('Las entradas suman existencias; las salidas las descuentan.'),
                TextInput::make('quantity')
                    ->label('Cantidad de unidades')
                    ->required()
                    ->numeric()
                    ->integer()
                    ->minValue(1),
                Textarea::make('description')
                    ->label('Descripción o motivo')
                    ->rows(3)
                    ->required()
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
