<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Seed the product families of the catalogue.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Bebidas'],
            ['name' => 'Lácteos'],
            ['name' => 'Abarrotes'],
            ['name' => 'Snacks'],
            ['name' => 'Limpieza'],
            ['name' => 'Higiene'],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(['name' => $category['name']], $category);
        }
    }
}
