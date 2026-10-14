<?php

namespace Database\Factories;

use App\Enums\MovementType;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InventoryMovement>
 */
class InventoryMovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'inventory_id' => Inventory::factory(),
            'type' => MovementType::Purchase,
            'quantity' => fake()->numberBetween(1, 50),
            'description' => 'Recepción de mercancía en almacén',
        ];
    }

    /**
     * Indicate that units leave the warehouse and the web platform.
     */
    public function salida(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => MovementType::Sale,
            'quantity' => -fake()->numberBetween(1, 50),
            'description' => 'Salida por venta en la plataforma web',
        ]);
    }
}
