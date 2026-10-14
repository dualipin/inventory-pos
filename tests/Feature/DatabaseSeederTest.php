<?php

use App\Models\Inventory;
use App\Models\InventoryMovement;

test('builds the seeded stock from its movement history', function () {
    $this->seed();

    Inventory::query()->with('movements')->get()->each(function (Inventory $inventory): void {
        $expected = $inventory->movements->sum(fn (InventoryMovement $movement) => $movement->quantity);

        expect($inventory->movements)->not->toBeEmpty()
            ->and($inventory->stock)->toBe($expected);
    });
});
