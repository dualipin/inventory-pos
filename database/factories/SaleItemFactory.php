<?php

namespace Database\Factories;

use App\Models\Inventory;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SaleItem>
 */
class SaleItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 5);
        $price = fake()->randomFloat(2, 10, 300);

        return [
            'sale_id' => Sale::factory(),
            'inventory_id' => Inventory::factory(),
            'quantity' => $quantity,
            'price' => $price,
            'subtotal' => round($quantity * $price, 2),
        ];
    }
}
