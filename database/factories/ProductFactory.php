<?php

namespace Database\Factories;

use App\Enums\ProductPackaging;
use App\Enums\ProductUnit;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = fake()->randomFloat(2, 19, 299);

        return [
            'category_id' => Category::factory(),
            'barcode' => fake()->unique()->ean13(),
            'name' => Str::title(fake()->unique()->words(3, true)),
            'price' => $price,
            'cost' => fake()->randomFloat(2, 10, max(10, $price - 1)),
            'packaging' => fake()->randomElement(ProductPackaging::cases()),
            'presentation' => fake()->realText(20),
            'size' => fake()->randomNumber(2),
            'unit' => fake()->randomElement(ProductUnit::cases()),
        ];
    }
}
