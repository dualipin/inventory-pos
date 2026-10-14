<?php

use App\Actions\CreateSaleAction;
use App\Enums\MovementType;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Validation\ValidationException;

test('creates a completed sale and records its inventory movement', function () {
    $cashier = User::factory()->create();
    $product = Product::factory()->create(['price' => 25.50, 'is_active' => true]);
    $inventory = Inventory::factory()->for($product)->create(['stock' => 10]);

    $sale = app(CreateSaleAction::class)->handle([
        'payment_method' => 'efectivo',
        'items' => [['inventory_id' => $inventory->id, 'quantity' => 2]],
    ], $cashier);

    expect($sale->user->is($cashier))->toBeTrue()
        ->and($sale->total)->toBe('51.00')
        ->and($sale->items)->toHaveCount(1)
        ->and($inventory->fresh()->stock)->toBe(8);

    $movement = InventoryMovement::query()->sole();

    expect($movement->type)->toBe(MovementType::Sale)
        ->and($movement->quantity)->toBe(-2)
        ->and($movement->reference->is($sale))->toBeTrue();
});

test('does not create a sale when stock is insufficient', function () {
    $cashier = User::factory()->create();
    $inventory = Inventory::factory()->create(['stock' => 1]);

    expect(fn () => app(CreateSaleAction::class)->handle([
        'payment_method' => 'efectivo',
        'items' => [['inventory_id' => $inventory->id, 'quantity' => 2]],
    ], $cashier))->toThrow(ValidationException::class);

    expect(Sale::query()->count())->toBe(0)
        ->and(InventoryMovement::query()->count())->toBe(0)
        ->and($inventory->fresh()->stock)->toBe(1);
});
