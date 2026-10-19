<?php

namespace App\Filament\Resources\Inventories\Tables;

use App\Filament\Resources\Inventories\InventoryResource;
use App\Models\Inventory;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class InventoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.category.name')
                    ->label('Familia')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('product.name')
                    ->label('Producto')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('stock')
                    ->label('Existencias')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (Inventory $record): string => match (true) {
                        $record->stock === 0 => 'danger',
                        $record->stock < 10 => 'warning',
                        default => 'success',
                    })
                    ->formatStateUsing(fn (int $state): string => "{$state} unidades"),
                TextColumn::make('stock_status')
                    ->label('Estado')
                    ->state(fn (Inventory $record): string => match (true) {
                        $record->stock === 0 => 'Agotado',
                        $record->stock < 10 => 'Stock bajo',
                        default => 'Disponible',
                    })
                    ->badge()
                    ->color(fn (Inventory $record): string => match (true) {
                        $record->stock === 0 => 'danger',
                        $record->stock < 10 => 'warning',
                        default => 'success',
                    }),
                TextColumn::make('last_counted_at')
                    ->label('Último conteo')
                    ->date()
                    ->sortable()
                    ->placeholder('Pendiente'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('stock_status')
                    ->label('Estado de existencias')
                    ->options([
                        'out' => 'Agotado',
                        'low' => 'Stock bajo',
                        'available' => 'Disponible',
                    ])
                    ->query(function ($query, array $data): void {
                        match ($data['value'] ?? null) {
                            'out' => $query->where('stock', 0),
                            'low' => $query->whereBetween('stock', [1, 9]),
                            'available' => $query->where('stock', '>=', 10),
                            default => null,
                        };
                    }),
                SelectFilter::make('last_counted_at')
                    ->label('Conteo físico')
                    ->options([
                        'pending' => 'Sin conteo',
                        'recent' => 'Conteo en los últimos 30 días',
                    ])
                    ->query(function ($query, array $data): void {
                        match ($data['value'] ?? null) {
                            'pending' => $query->whereNull('last_counted_at'),
                            'recent' => $query->where('last_counted_at', '>=', now()->subDays(30)),
                            default => null,
                        };
                    }),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorizeIndividualRecords(fn (Model $record): bool => InventoryResource::canDelete($record)),
                ]),
            ]);
    }
}
