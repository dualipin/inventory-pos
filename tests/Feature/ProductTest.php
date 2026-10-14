<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('relates to its category', function () {
    $product = Product::factory()->create();
    expect($product->category)->toBeInstanceOf(Category::class);
});

test('returns the price with two decimal places', function () {
    $product = Product::factory()->create(['price' => 27.9]);

    expect($product->price)->toBe('27.90');
});

test('keeps a single product image', function () {
    Storage::fake('public');

    $product = Product::factory()->create();

    $product->addMedia(UploadedFile::fake()->image('leche.jpg'))->toMediaCollection('image');
    $product->addMedia(UploadedFile::fake()->image('leche-alternativa.jpg'))->toMediaCollection('image');

    expect($product->getMedia('image'))->toHaveCount(1)
        ->and($product->getFirstMedia('image')->hasGeneratedConversion('thumb'))->toBeTrue();
});

test('creates its inventory record once and only once', function () {
    $product = Product::factory()->create();

    $inventory = $product->ensureInventory();

    expect($inventory->product_id)->toBe($product->id)
        ->and($inventory->stock)->toBe(0)
        ->and($product->ensureInventory()->id)->toBe($inventory->id)
        ->and($product->inventory()->count())->toBe(1);
});
