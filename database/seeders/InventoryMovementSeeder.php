<?php

namespace Database\Seeders;

use App\Enums\MovementType;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use Illuminate\Database\Seeder;

class InventoryMovementSeeder extends Seeder
{
    /**
     * Seed the stock history, building the current stock from entry movements.
     */
    public function run(): void
    {
        Inventory::query()
            ->whereDoesntHave('movements')
            ->get()
            ->each(function (Inventory $inventory): void {
                $reception = fake()->numberBetween(120, 400);

                InventoryMovement::query()->create([
                    'inventory_id' => $inventory->id,
                    'type' => MovementType::Purchase,
                    'quantity' => $reception,
                    'description' => 'Recepción inicial de mercancía en almacén',
                ]);

                if (fake()->boolean(60)) {
                    InventoryMovement::query()->create([
                        'inventory_id' => $inventory->id,
                        'type' => MovementType::Sale,
                        'quantity' => -fake()->numberBetween(10, min(50, $reception)),
                        'description' => 'Salida por venta en la plataforma web',
                    ]);
                }
            });
    }
}
