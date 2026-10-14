<?php

use App\Enums\MovementType;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use Illuminate\Validation\ValidationException;

test('increases the physical and digital stock when an entry is recorded', function () {
    $inventory = Inventory::factory()->create(['physical_stock' => 10, 'digital_stock' => 10]);

    $movement = InventoryMovement::factory()->for($inventory)->create(['quantity' => 25]);

    $inventory->refresh();

    expect($movement->type)->toBe(MovementType::Entrada)
        ->and($movement->inventory->is($inventory))->toBeTrue()
        ->and($inventory->physical_stock)->toBe(35)
        ->and($inventory->digital_stock)->toBe(35);
});

test('decreases the physical and digital stock when an exit is recorded', function () {
    $inventory = Inventory::factory()->create(['stock' => 100]);

    InventoryMovement::factory()->for($inventory)->salida()->create(['quantity' => -30]);

    $inventory->refresh();

    expect($inventory->stock)->toBe(70);
});

test('rejects an exit when the stock is not enough', function () {
    $inventory = Inventory::factory()->create(['physical_stock' => 40, 'digital_stock' => 8]);

    expect(fn () => InventoryMovement::factory()->for($inventory)->salida()->create(['quantity' => 10]))
        ->toThrow(ValidationException::class);

    $inventory->refresh();

    expect($inventory->stock)->toBe(40)
        ->and($inventory->movements()->count())->toBe(0);
});

test('rejects a movement with a quantity lower than one', function () {
    $inventory = Inventory::factory()->create(['stock' => 40]);

    expect(fn () => InventoryMovement::factory()->for($inventory)->create(['quantity' => 0]))
        ->toThrow(ValidationException::class);

    expect($inventory->movements()->count())->toBe(0);
});

test('rejects a movement without an inventory', function () {
    expect(fn () => InventoryMovement::factory()->create(['inventory_id' => null]))
        ->toThrow(ValidationException::class);

    expect(InventoryMovement::count())->toBe(0);
});
