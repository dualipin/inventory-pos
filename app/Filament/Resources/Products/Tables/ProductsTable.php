<?php

namespace App\Filament\Resources\Products\Tables;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')
                    ->label('Imagen')
                    ->collection('image')
                    ->conversion('thumb'),
                TextColumn::make('category.name')
                    ->label('Familia')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nombre comercial')
                    ->searchable(),
                TextColumn::make('barcode')
                    ->label('Código de barras')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('price')
                    ->label('Precio')
                    ->money('MXN')
                    ->sortable(),
                TextColumn::make('cost')
                    ->label('Costo')
                    ->money('MXN')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('inventory.stock')
                    ->label('Stock')
                    ->sortable(),
                TextColumn::make('packaging')
                    ->label('Empaque')
                    ->badge()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('unit')
                    ->label('Unidad')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('presentation')
                    ->label('Presentación')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('size')
                    ->label('Tamaño')
                    ->searchable(),
                ToggleColumn::make('is_active')
                    ->label('Activo'),
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
                TernaryFilter::make('is_active')
                    ->label('Estado')
                    ->trueLabel('Solo activos')
                    ->falseLabel('Solo inactivos'),
                TrashedFilter::make(),
            ])
            ->defaultSort('name')
            ->recordActions([
                EditAction::make()
                    ->hidden(fn (Model $record): bool => $record->trashed()),
                RestoreAction::make(),
                ForceDeleteAction::make()
                    ->visible(fn (Model $record): bool => $record->trashed()
                        && ProductResource::canDelete($record)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorizeIndividualRecords(fn (Model $record): bool => ProductResource::canDelete($record)),
                ]),
            ]);
    }
}
