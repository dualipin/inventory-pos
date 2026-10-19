<?php

namespace App\Filament\Resources\Promotions\Tables;

use App\Enums\DiscountType;
use App\Filament\Resources\Promotions\PromotionResource;
use App\Models\Promotion;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PromotionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('banner')
                    ->label('Banner')
                    ->collection('banner')
                    ->conversion('thumb'),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50)
                    ->wrap()
                    ->placeholder('Sin descripción')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('discount_type')
                    ->label('Descuento')
                    ->state(fn (Promotion $record): string => match ($record->discount_type) {
                        DiscountType::Percent => number_format((float) $record->discount_value, 0).'%',
                        DiscountType::Fixed => '$'.number_format((float) $record->discount_value, 2),
                    })
                    ->badge(),
                TextColumn::make('products_count')
                    ->label('Productos')
                    ->counts('products')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('starts_at')
                    ->label('Inicio')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('ends_at')
                    ->label('Fin')
                    ->dateTime()
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Activa'),
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
                Filter::make('is_active')
                    ->label('Solo promociones activas')
                    ->toggle()
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true)),
            ])
            ->defaultSort('starts_at', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorizeIndividualRecords(fn (Model $record): bool => PromotionResource::canDelete($record)),
                ]),
            ]);
    }
}
