<?php

use App\Enums\ProductPackaging;
use App\Livewire\ProductCatalogue;
use App\Models\Inventory;
use App\Models\Product;
use Livewire\Livewire;

it('lists the active products of the catalogue', function () {
    $active = Product::factory()->create(['name' => 'Leche Entera', 'is_active' => true]);
    $inactive = Product::factory()->create(['name' => 'Yogurt Natural', 'is_active' => false]);

    Livewire::test(ProductCatalogue::class)
        ->assertSee($active->name)
        ->assertDontSee($inactive->name);
});

it('finds a product by the label of its packaging', function () {
    $bottle = Product::factory()->create([
        'name' => 'Leche Entera',
        'packaging' => ProductPackaging::Bottle,
        'is_active' => true,
    ]);
    $bucket = Product::factory()->create([
        'name' => 'Yogurt Familiar',
        'packaging' => ProductPackaging::Bucket,
        'is_active' => true,
    ]);

    Livewire::test(ProductCatalogue::class)
        ->set('search', 'Botella')
        ->assertSee($bottle->name)
        ->assertDontSee($bucket->name);
});

it('lists only the products with stock when the filter is enabled', function () {
    $available = Product::factory()->create(['name' => 'Leche Entera', 'is_active' => true]);
    Inventory::factory()->create(['product_id' => $available->id, 'stock' => 10]);

    $soldOut = Product::factory()->create(['name' => 'Yogurt Natural', 'is_active' => true]);
    Inventory::factory()->create(['product_id' => $soldOut->id, 'stock' => 0]);

    Livewire::test(ProductCatalogue::class)
        ->set('onlyInStock', true)
        ->assertSee($available->name)
        ->assertDontSee($soldOut->name);
});
