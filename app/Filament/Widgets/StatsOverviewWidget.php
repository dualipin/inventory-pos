<?php

namespace App\Filament\Widgets;

use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Promotion;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalProducts = Product::count();

        $lowStockCount = Inventory::query()
            ->where('stock', '<', 10)
            ->count();

        $activePromotions = Promotion::query()
            ->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->count();

        $movementsToday = InventoryMovement::query()
            ->whereDate('created_at', today())
            ->count();

        return [
            Stat::make('Productos', $totalProducts)
                ->description('Total en catálogo')
                ->icon('heroicon-o-shopping-bag')
                ->color('primary'),

            Stat::make('Stock bajo', $lowStockCount)
                ->description('Menos de 10 unidades')
                ->icon('heroicon-o-exclamation-triangle')
                ->color($lowStockCount > 0 ? 'danger' : 'success'),

            Stat::make('Promociones activas', $activePromotions)
                ->description('En vigor ahora mismo')
                ->icon('heroicon-o-tag')
                ->color('warning'),

            Stat::make('Movimientos hoy', $movementsToday)
                ->description('Entradas y salidas del día')
                ->icon('heroicon-o-arrows-right-left')
                ->color('gray'),
        ];
    }
}
