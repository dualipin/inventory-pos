<?php

use App\Enums\UserRole;
use App\Filament\Pages\PointOfSale;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Inventories\InventoryResource;
use App\Filament\Resources\InventoryMovements\InventoryMovementResource;
use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Promotions\PromotionResource;
use App\Filament\Resources\Sales\SaleResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;

it('gives the administrator access to every operational area', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $this->actingAs($admin);

    expect(PointOfSale::canAccess())->toBeTrue()
        ->and(SaleResource::canViewAny())->toBeTrue()
        ->and(InventoryResource::canViewAny())->toBeTrue()
        ->and(InventoryMovementResource::canViewAny())->toBeTrue()
        ->and(ProductResource::canViewAny())->toBeTrue()
        ->and(CategoryResource::canViewAny())->toBeTrue()
        ->and(PromotionResource::canViewAny())->toBeTrue()
        ->and(UserResource::canViewAny())->toBeTrue();
});

it('limits the cashier to the point of sale and sales history', function () {
    $cashier = User::factory()->create(['role' => UserRole::CASHIER]);
    $this->actingAs($cashier);

    expect(PointOfSale::canAccess())->toBeTrue()
        ->and(SaleResource::canViewAny())->toBeTrue()
        ->and(InventoryResource::canViewAny())->toBeFalse()
        ->and(InventoryMovementResource::canViewAny())->toBeFalse()
        ->and(ProductResource::canViewAny())->toBeFalse()
        ->and(PromotionResource::canViewAny())->toBeFalse()
        ->and(UserResource::canViewAny())->toBeFalse();
});

it('limits the inventory role to catalog and stock operations', function () {
    $inventoryManager = User::factory()->create(['role' => UserRole::INVENTORY]);
    $this->actingAs($inventoryManager);

    expect(PointOfSale::canAccess())->toBeFalse()
        ->and(SaleResource::canViewAny())->toBeFalse()
        ->and(InventoryResource::canViewAny())->toBeTrue()
        ->and(InventoryMovementResource::canViewAny())->toBeTrue()
        ->and(ProductResource::canViewAny())->toBeTrue()
        ->and(CategoryResource::canViewAny())->toBeTrue()
        ->and(PromotionResource::canViewAny())->toBeFalse()
        ->and(UserResource::canViewAny())->toBeFalse();
});
