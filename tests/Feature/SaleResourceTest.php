<?php

use App\Actions\CancelSaleAction;
use App\Enums\MovementType;
use App\Enums\SaleStatus;
use App\Enums\UserRole;
use App\Filament\Resources\Sales\Pages\ListSales;
use App\Filament\Resources\Sales\SaleResource;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory(['role' => UserRole::ADMIN])->create());
});

it('cancels a sale and returns the units to the inventory', function () {
    $product = Product::factory()->create();
    $inventory = Inventory::factory()->create(['product_id' => $product->id, 'stock' => 3]);
    $sale = Sale::factory()->create();
    SaleItem::factory()->create([
        'sale_id' => $sale->id,
        'inventory_id' => $inventory->id,
        'quantity' => 2,
    ]);

    app(CancelSaleAction::class)->handle($sale);

    expect($sale->fresh()->estatus)->toBe(SaleStatus::Cancelled)
        ->and($inventory->fresh()->stock)->toBe(5);

    $movement = InventoryMovement::query()->sole();

    expect($movement->type)->toBe(MovementType::ReturnIn)
        ->and($movement->quantity)->toBe(2)
        ->and($movement->reference->is($sale))->toBeTrue();
});

it('refuses to cancel a sale that is already cancelled', function () {
    $sale = Sale::factory()->cancelled()->create();

    expect(fn () => app(CancelSaleAction::class)->handle($sale))
        ->toThrow(ValidationException::class);
});

it('offers the cancel action only for a completed sale', function () {
    $sale = Sale::factory()->create();

    Livewire::test(ListSales::class)
        ->assertTableActionVisible('cancel', $sale);

    $cancelledSale = Sale::factory()->cancelled()->create();

    Livewire::test(ListSales::class)
        ->assertTableActionHidden('cancel', $cancelledSale);
});

it('lets an administrator cancel a sale of another cashier', function () {
    $cashier = User::factory()->create(['role' => UserRole::CASHIER]);
    $sale = Sale::factory()->create(['user_id' => $cashier->id]);

    expect(SaleResource::canCancel($sale))->toBeTrue();
});

it('forbids a cashier from cancelling a sale of another cashier', function () {
    $otherCashier = User::factory()->create(['role' => UserRole::CASHIER]);
    $sale = Sale::factory()->create(['user_id' => $otherCashier->id]);

    $this->actingAs(User::factory()->create(['role' => UserRole::CASHIER]));

    expect(SaleResource::canCancel($sale))->toBeFalse();
});

it('lets a cashier cancel their own sale', function () {
    $cashier = User::factory()->create(['role' => UserRole::CASHIER]);
    $sale = Sale::factory()->create(['user_id' => $cashier->id]);

    $this->actingAs($cashier);

    expect(SaleResource::canCancel($sale))->toBeTrue();
});

it('forbids editing and deleting a sale', function () {
    $sale = Sale::factory()->create();

    expect(SaleResource::canEdit($sale))->toBeFalse()
        ->and(SaleResource::canDelete($sale))->toBeFalse();
});
