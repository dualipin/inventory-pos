<?php

namespace App\Filament\Resources\Sales\Tables;

use App\Actions\CancelSaleAction;
use App\Enums\SaleStatus;
use App\Filament\Resources\Sales\SaleResource;
use App\Models\Sale;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\ValidationException;

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
            ->recordActions([
                ViewAction::make(),
                Action::make('cancel')
                    ->label('Cancelar venta')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('¿Cancelar la venta?')
                    ->modalDescription('Las unidades vuelven al inventario y la venta queda marcada como cancelada.')
                    ->modalSubmitActionLabel('Sí, cancelar la venta')
                    ->visible(fn (Sale $record): bool => $record->estatus === SaleStatus::Completed
                        && SaleResource::canCancel($record))
                    ->action(fn (Sale $record) => self::cancel($record)),
            ]);
    }

    private static function cancel(Sale $record): void
    {
        if (! SaleResource::canCancel($record)) {
            Notification::make()
                ->danger()
                ->title('No puedes cancelar esta venta')
                ->send();

            return;
        }

        try {
            app(CancelSaleAction::class)->handle($record);
        } catch (ValidationException $exception) {
            Notification::make()
                ->danger()
                ->title('No se pudo cancelar la venta')
                ->body($exception->validator->errors()->first())
                ->send();

            return;
        }

        Notification::make()
            ->success()
            ->title('Venta cancelada')
            ->body("Folio {$record->folio}")
            ->send();
    }
}
