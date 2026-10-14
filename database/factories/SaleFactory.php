<?php

namespace Database\Factories;

use App\Enums\PaymentMethod;
use App\Enums\SaleStatus;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'folio' => fake()->unique()->bothify('VTA-####-????'),
            'total' => fake()->randomFloat(2, 20, 1000),
            'payment_method' => PaymentMethod::Cash,
            'estatus' => SaleStatus::Completed,
        ];
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes): array => [
            'estatus' => SaleStatus::Cancelled,
        ]);
    }
}
