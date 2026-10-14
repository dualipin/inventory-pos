<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed the commercial catalogue and its inventory records.
     */
    public function run(): void
    {
        $products = [
            // --- BEBIDAS ---
            ['name' => 'Coca-Cola Original 600ml', 'category' => 'Bebidas', 'price' => 18.50, 'cost' => 12.00],
            ['name' => 'Refresco Pepsi 2L', 'category' => 'Bebidas', 'price' => 28.00, 'cost' => 19.00],
            ['name' => 'Agua Mineral Peñafiel 600ml', 'category' => 'Bebidas', 'price' => 14.00, 'cost' => 9.00],
            ['name' => 'Jugo de Naranja Del Valle 1L', 'category' => 'Bebidas', 'price' => 24.50, 'cost' => 16.00],
            ['name' => 'Agua Purificada Ciel 1.5L', 'category' => 'Bebidas', 'price' => 13.00, 'cost' => 8.00],

            // --- LÁCTEOS Y DERIVADOS ---
            ['name' => 'Leche Entera Lala 1L', 'category' => 'Lácteos', 'price' => 26.00, 'cost' => 18.00],
            ['name' => 'Leche Deslactosada Alpura 1L', 'category' => 'Lácteos', 'price' => 27.50, 'cost' => 19.00],
            ['name' => 'Yogurt Griego Fage 150g', 'category' => 'Lácteos', 'price' => 22.00, 'cost' => 15.00],
            ['name' => 'Mantequilla Con Sal Gloria 90g', 'category' => 'Lácteos', 'price' => 19.00, 'cost' => 13.00],
            ['name' => 'Queso Panela Nochebuena 400g', 'category' => 'Lácteos', 'price' => 68.00, 'cost' => 48.00],

            // --- DESPENSA / ABARROTES ---
            ['name' => 'Arroz Blanco Verde Valle 1kg', 'category' => 'Abarrotes', 'price' => 34.00, 'cost' => 24.00],
            ['name' => 'Frijol Negro Verde Valle 1kg', 'category' => 'Abarrotes', 'price' => 42.00, 'cost' => 30.00],
            ['name' => 'Aceite Vegetal 1-2-3 1L', 'category' => 'Abarrotes', 'price' => 39.50, 'cost' => 28.00],
            ['name' => 'Atún en Agua Herdez 130g', 'category' => 'Abarrotes', 'price' => 21.00, 'cost' => 15.00],
            ['name' => 'Pasta Spaguetti La Moderna 200g', 'category' => 'Abarrotes', 'price' => 11.50, 'cost' => 8.00],
            ['name' => 'Mayonesa McCormick con Limón 390g', 'category' => 'Abarrotes', 'price' => 48.00, 'cost' => 34.00],
            ['name' => 'Puré de Tomate Del Fuerte 210g', 'category' => 'Abarrotes', 'price' => 9.00, 'cost' => 6.00],

            // --- SNACKS Y GALLETAS ---
            ['name' => 'Papas Sabritas Sal 170g', 'category' => 'Snacks', 'price' => 44.00, 'cost' => 31.00],
            ['name' => 'Doritos Nacho Sabritas 146g', 'category' => 'Snacks', 'price' => 38.00, 'cost' => 27.00],
            ['name' => 'Galletas Marías Gamesa 170g', 'category' => 'Snacks', 'price' => 17.00, 'cost' => 12.00],
            ['name' => 'Galletas Chokis Gamesa 63g', 'category' => 'Snacks', 'price' => 16.50, 'cost' => 11.00],

            // --- LIMPIEZA Y HOGAR ---
            ['name' => 'Detergente Ariel Poder y Cuidado 1kg', 'category' => 'Limpieza', 'price' => 46.00, 'cost' => 32.00],
            ['name' => 'Jabón Líquido Axion Limón 400ml', 'category' => 'Limpieza', 'price' => 29.00, 'cost' => 20.00],
            ['name' => 'Cloro Cloralex Rendidor 950ml', 'category' => 'Limpieza', 'price' => 19.50, 'cost' => 13.00],
            ['name' => 'Limpiador Fabuloso Fresca Lavanda 1L', 'category' => 'Limpieza', 'price' => 28.00, 'cost' => 19.00],

            // --- HIGIENE PERSONAL ---
            ['name' => 'Jabón de Barra Palmolive Neutro 120g', 'category' => 'Higiene', 'price' => 18.00, 'cost' => 12.00],
            ['name' => 'Pasta Dental Colgate Total 12 100ml', 'category' => 'Higiene', 'price' => 36.50, 'cost' => 25.00],
            ['name' => 'Shampoo Caprice Especialidades 750ml', 'category' => 'Higiene', 'price' => 42.00, 'cost' => 29.00],
        ];

        foreach ($products as $attributes) {
            $category = Category::query()->where('name', $attributes['category'])->firstOrFail();
            unset($attributes['category']);
            $attributes['barcode'] ??= fake()->unique()->ean13();

            $product = Product::query()->updateOrCreate(
                ['name' => $attributes['name']],
                $attributes + ['category_id' => $category->id],
            );

            $inventory = $product->ensureInventory();

            if ($inventory->wasRecentlyCreated) {
                $inventory->update([
                    'last_counted_at' => fake()->dateTimeBetween('-15 days'),
                ]);
            }
        }
    }
}
