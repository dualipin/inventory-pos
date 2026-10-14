<?php

namespace App\Filament\Resources\InventoryMovements\Schemas;

use App\Enums\MovementType;
use App\Models\Inventory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InventoryMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('¿Qué pasó con el inventario?')
                    ->description('Selecciona el producto y el tipo de cambio que necesitas registrar.')
                    ->icon('heroicon-o-arrows-right-left')
                    ->schema([
                        Select::make('inventory_id')
                            ->label('Producto')
                            ->options(fn (): array => Inventory::query()
                                ->with('product:id,name')
                                ->get()
                                ->mapWithKeys(fn (Inventory $inventory): array => [
                                    $inventory->id => sprintf(
                                        '%s — %s %s disponibles',
                                        $inventory->product?->name,
                                        number_format($inventory->stock),
                                        $inventory->stock === 1 ? 'unidad' : 'unidades',
                                    ),
                                ])
                                ->all())
                            ->searchable()
                            ->searchPrompt('Busca por nombre de producto')
                            ->placeholder('Selecciona un producto')
                            ->preload()
                            ->required()
                            ->helperText('También verás cuántas unidades hay disponibles.'),
                        Select::make('type')
                            ->label('¿Qué tipo de cambio es?')
                            ->options(MovementType::class)
                            ->default(MovementType::Purchase)
                            ->required()
                            ->helperText('Compra y devolución suman unidades. Venta y merma las restan. Un ajuste corrige el total.'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('¿Cuántas unidades y por qué?')
                    ->description('Estos datos ayudan a entender el historial del inventario.')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        TextInput::make('quantity')
                            ->label('Cantidad de unidades')
                            ->required()
                            ->numeric()
                            ->integer()
                            ->minValue(1)
                            ->suffix('unidades')
                            ->helperText('Escribe solo el número. No necesitas usar signos + o -.'),
                        Textarea::make('description')
                            ->label('Motivo')
                            ->placeholder('Ejemplo: Recepción de mercancía del proveedor')
                            ->helperText('Describe brevemente qué originó este cambio.')
                            ->rows(3)
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
