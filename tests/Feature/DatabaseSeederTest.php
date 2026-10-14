<?php

use App\Models\Category;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\User;

test('seeds the catalogue, the innovation history and the admin user', function () {
    $this->seed();

    expect(Category::count())->toBe(5)
        ->and(Product::count())->toBe(14)
        ->and(Inventory::count())->toBe(14)
        ->and(Promotion::count())->toBe(3)
        ->and(User::query()->where('email', 'admin@lala.test')->exists())->toBeTrue();
});

test('builds the seeded stock from its movement history', function () {
    $this->seed();

    Inventory::query()->with('movements')->get()->each(function (Inventory $inventory): void {
        $expected = $inventory->movements->sum(fn (InventoryMovement $movement) => $movement->quantity);

        expect($inventory->movements)->not->toBeEmpty()
            ->and($inventory->stock)->toBe($expected);
    });
});
