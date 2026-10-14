<?php

namespace App\Filament\Resources\Sales\Schemas;

use App\Enums\PaymentMethod;
use App\Models\Inventory;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('payment_method')
                ->label('Método de pago')
                ->options(PaymentMethod::class)
                ->default(PaymentMethod::Cash)
                ->required(),
            Repeater::make('items')
                ->label('Productos')
                ->schema([
                    Select::make('inventory_id')
                        ->label('Producto')
                        ->options(fn (): array => Inventory::query()
                            ->with('product:id,name')
                            ->whereHas('product', fn ($query) => $query->where('is_active', true))
                            ->get()
                            ->mapWithKeys(fn (Inventory $inventory): array => [
                                $inventory->id => "{$inventory->product->name} (stock: {$inventory->stock})",
                            ])
                            ->all())
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('quantity')
                        ->label('Cantidad')
                        ->numeric()
                        ->integer()
                        ->minValue(1)
                        ->required(),
                ])
                ->columns(2)
                ->minItems(1)
                ->addActionLabel('Agregar producto')
                ->columnSpanFull()
                ->required(),
        ])->columns(2);
    }
}
