<?php

namespace App\Filament\Widgets;

use App\Enums\MovementType;
use App\Models\InventoryMovement;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseTableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentMovementsWidget extends BaseTableWidget
{
    protected static ?string $heading = 'Movimientos recientes';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn (): Builder => InventoryMovement::query()
                    ->with(['inventory.product'])
                    ->latest()
                    ->limit(8)
            )
            ->columns([
                Tables\Columns\TextColumn::make('inventory.product.name')
                    ->label('Producto')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->alignEnd()
                    ->formatStateUsing(
                        fn (InventoryMovement $record) => $record->quantity
                    )
                    ->color(fn (InventoryMovement $record): string => $record->type === MovementType::Purchase ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Nota')
                    ->limit(50)
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->since()
                    ->tooltip(fn (InventoryMovement $record): string => $record->created_at->format('d/m/Y H:i')),
            ])
            ->paginated(false)
            ->striped();
    }
}
