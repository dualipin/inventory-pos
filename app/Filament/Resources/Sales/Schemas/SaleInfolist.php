<?php

namespace App\Filament\Resources\Sales\Schemas;

use App\Enums\SaleStatus;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class SaleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos de la venta')
                    ->schema([
                        TextEntry::make('folio')
                            ->label('Folio'),
                        TextEntry::make('created_at')
                            ->label('Fecha y hora')
                            ->dateTime('d/m/Y H:i:s'),
                        TextEntry::make('user.name')
                            ->label('Cajero')
                            ->placeholder('Sin cajero asignado'),
                        TextEntry::make('payment_method')
                            ->label('Método de pago')
                            ->badge(),
                        TextEntry::make('estatus')
                            ->label('Estado')
                            ->badge()
                            ->color(fn (SaleStatus $state): string => $state->getColor()),
                        TextEntry::make('total')
                            ->label('Total')
                            ->money('MXN')
                            ->weight(FontWeight::Bold),
                    ])
                    ->columns(3),
                Section::make('Productos')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->label('')
                            ->table([
                                TableColumn::make('Producto'),
                                TableColumn::make('Cantidad'),
                                TableColumn::make('Precio unitario'),
                                TableColumn::make('Subtotal'),
                            ])
                            ->schema([
                                TextEntry::make('inventory.product.name')
                                    ->label('Producto')
                                    ->placeholder('Producto no disponible'),
                                TextEntry::make('quantity')
                                    ->label('Cantidad'),
                                TextEntry::make('price')
                                    ->label('Precio unitario')
                                    ->money('MXN'),
                                TextEntry::make('subtotal')
                                    ->label('Subtotal')
                                    ->money('MXN')
                                    ->weight(FontWeight::Bold),
                            ]),
                    ]),
            ]);
    }
}
