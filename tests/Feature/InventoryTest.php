<?php

use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\UniqueConstraintViolationException;

test('relates its stock to the product and to its movements', function () {
    $inventory = Inventory::factory()->create();
    $movement = InventoryMovement::factory()->for($inventory)->create();

    expect($inventory->product)->toBeInstanceOf(Product::class)
        ->and($inventory->movements)->toHaveCount(1)
        ->and($inventory->movements->first()->is($movement))->toBeTrue();
});

test('allows only one inventory record per product', function () {
    $inventory = Inventory::factory()->create();

    $this->expectException(UniqueConstraintViolationException::class);

    Inventory::factory()->create(['product_id' => $inventory->product_id]);
});

test('casts the stock counters and the last physical count', function () {
    $inventory = Inventory::factory()->create([
        'stock' => 120,
        'last_counted_at' => '2026-09-30 10:00:00',
    ]);

    expect($inventory->stock)->toBe(120)
        ->and($inventory->last_counted_at)->toBeInstanceOf(Carbon::class)
        ->and($inventory->last_counted_at->toDateTimeString())->toBe('2026-09-30 10:00:00');
});
