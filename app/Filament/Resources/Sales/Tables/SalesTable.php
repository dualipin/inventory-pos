<?php

namespace App\Filament\Resources\Sales\Tables;

use App\Enums\SaleStatus;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('folio')->label('Folio')->searchable()->sortable(),
                TextColumn::make('user.name')->label('Cajero')->searchable()->sortable(),
                TextColumn::make('total')->label('Total')->money('MXN')->sortable(),
                TextColumn::make('payment_method')->label('Pago')->badge(),
                TextColumn::make('estatus')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (SaleStatus $state): string => $state->getColor()),
                TextColumn::make('created_at')->label('Fecha')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([ViewAction::make()]);
    }
}
