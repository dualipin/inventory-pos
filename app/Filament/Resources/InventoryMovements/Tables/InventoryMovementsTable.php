<?php

namespace App\Filament\Resources\InventoryMovements\Tables;

use App\Models\InventoryMovement;
use App\Models\Sale;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InventoryMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('inventory.product.name')
                    ->label('Producto')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge(),
                TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50)
                    ->wrap()
                    ->placeholder('Sin descripción'),
                TextColumn::make('reference')
                    ->label('Referencia')
                    ->state(fn (InventoryMovement $record): ?string => match (true) {
                        $record->reference instanceof Sale => $record->reference->folio,
                        $record->reference !== null => sprintf(
                            '%s #%s',
                            class_basename($record->reference),
                            $record->reference->getKey(),
                        ),
                        default => null,
                    })
                    ->placeholder('Sin referencia')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([])
            ->toolbarActions([])
            ->modifyQueryUsing(fn ($query) => $query->with(['inventory.product', 'reference']));
    }
}
