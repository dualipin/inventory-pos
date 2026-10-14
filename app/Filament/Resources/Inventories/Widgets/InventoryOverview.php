<?php

namespace App\Filament\Resources\Inventories\Widgets;

use App\Models\Inventory;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InventoryOverview extends BaseStatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalProducts = Inventory::query()->count();
        $totalUnits = Inventory::query()->sum('stock');
        $outOfStock = Inventory::query()->where('stock', 0)->count();
        $lowStock = Inventory::query()->whereBetween('stock', [1, 9])->count();
        $pendingCounts = Inventory::query()->whereNull('last_counted_at')->count();

        return [
            Stat::make('Unidades en existencia', number_format($totalUnits))
                ->description("En {$totalProducts} productos")
                ->icon('heroicon-o-cube')
                ->color('primary'),
            Stat::make('Stock bajo', $lowStock)
                ->description('Entre 1 y 9 unidades')
                ->icon('heroicon-o-exclamation-triangle')
                ->color($lowStock > 0 ? 'warning' : 'success'),
            Stat::make('Agotados', $outOfStock)
                ->description('Requieren reposición')
                ->icon('heroicon-o-x-circle')
                ->color($outOfStock > 0 ? 'danger' : 'success'),
            Stat::make('Conteos pendientes', $pendingCounts)
                ->description('Sin conteo físico registrado')
                ->icon('heroicon-o-clipboard-document-check')
                ->color($pendingCounts > 0 ? 'warning' : 'success'),
        ];
    }
}
