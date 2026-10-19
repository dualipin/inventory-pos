<?php

use App\Enums\MovementType;
use App\Enums\UserRole;
use App\Filament\Resources\Inventories\InventoryResource;
use App\Filament\Resources\InventoryMovements\InventoryMovementResource;
use App\Filament\Resources\InventoryMovements\Pages\CreateInventoryMovement;
use App\Filament\Resources\InventoryMovements\Pages\ListInventoryMovements;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory(['role' => UserRole::ADMIN])->create());
});

it('titles an inventory record with the name of its product', function () {
    $product = Product::factory()->create(['name' => 'Yogurt Natural']);
    $inventory = Inventory::factory()->create(['product_id' => $product->id]);

    expect(InventoryResource::getRecordTitle($inventory))->toBe('Yogurt Natural');
});

it('titles a movement with its description', function () {
    $movement = InventoryMovement::factory()->create(['description' => 'Recepción de mercancía']);

    expect(InventoryMovementResource::getRecordTitle($movement))->toBe('Recepción de mercancía');
});

it('titles a movement without description with its product and type', function () {
    $product = Product::factory()->create(['name' => 'Yogurt Natural']);
    $inventory = Inventory::factory()->create(['product_id' => $product->id]);
    $movement = InventoryMovement::factory()->create([
        'inventory_id' => $inventory->id,
        'type' => MovementType::Purchase,
        'description' => null,
    ]);

    expect(InventoryMovementResource::getRecordTitle($movement))->toBe('Yogurt Natural — Compra');
});

it('discounts the stock when a negative adjustment is recorded', function () {
    $inventory = Inventory::factory()->create(['stock' => 10]);

    Livewire::test(CreateInventoryMovement::class)
        ->fillForm([
            'inventory_id' => $inventory->id,
            'type' => MovementType::Adjustment->value,
            'quantity' => -3,
            'description' => 'Corrección de conteo físico',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect($inventory->fresh()->stock)->toBe(7);
});

it('rejects a negative quantity for a movement that is not an adjustment', function () {
    $inventory = Inventory::factory()->create(['stock' => 10]);

    Livewire::test(CreateInventoryMovement::class)
        ->fillForm([
            'inventory_id' => $inventory->id,
            'type' => MovementType::Purchase->value,
            'quantity' => -3,
            'description' => 'Recepción de mercancía',
        ])
        ->call('create')
        ->assertHasFormErrors(['quantity']);

    expect($inventory->fresh()->stock)->toBe(10)
        ->and(InventoryMovement::query()->count())->toBe(0);
});

it('records a movement without a description', function () {
    $inventory = Inventory::factory()->create(['stock' => 10]);

    Livewire::test(CreateInventoryMovement::class)
        ->fillForm([
            'inventory_id' => $inventory->id,
            'type' => MovementType::Purchase->value,
            'quantity' => 5,
            'description' => null,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(InventoryMovement::query()->sole()->description)->toBeNull()
        ->and($inventory->fresh()->stock)->toBe(15);
});

it('shows the sale folio as the reference of a movement', function () {
    $inventory = Inventory::factory()->create();
    $sale = Sale::factory()->create(['folio' => 'VTA-20260101-ABC123']);

    $movement = new InventoryMovement([
        'inventory_id' => $inventory->id,
        'type' => MovementType::Sale,
        'quantity' => -1,
        'description' => 'Venta de prueba',
    ]);
    $movement->reference()->associate($sale);
    $movement->save();

    Livewire::test(ListInventoryMovements::class)
        ->assertTableColumnExists('reference')
        ->assertTableColumnStateSet('reference', 'VTA-20260101-ABC123', $movement);
});
