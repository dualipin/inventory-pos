<?php

namespace App\Filament\Resources\Inventories\Pages;

use App\Filament\Resources\Inventories\InventoryResource;
use App\Filament\Resources\Inventories\Widgets\InventoryOverview;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInventories extends ListRecords
{
    protected static string $resource = InventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('registerMovement')
                ->label('Registrar movimiento')
                ->icon('heroicon-o-arrows-right-left')
                ->url(fn (): string => route('filament.admin.resources.inventory-movements.create')),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            InventoryOverview::class,
        ];
    }
}
