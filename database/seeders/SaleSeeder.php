<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    /**
     * Seed completed sales and their line items.
     */
    public function run(): void
    {
        if (Sale::query()->exists()) {
            return;
        }

        Inventory::query()
            ->with('product')
            ->get()
            ->each(function (Inventory $inventory): void {
                $quantity = min(3, max(1, $inventory->stock));
                $price = (float) $inventory->product->price;
                $subtotal = round($quantity * $price, 2);
                $sale = Sale::factory()->create(['total' => $subtotal]);

                SaleItem::factory()->create([
                    'sale_id' => $sale->id,
                    'inventory_id' => $inventory->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);
            });
    }
}
