<?php

use App\Actions\CreateSaleAction;
use App\Enums\UserRole;
use App\Filament\Pages\PointOfSale;
use App\Filament\Resources\Sales\SaleResource;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\UserResource;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Locked;
use Livewire\Livewire;

it('forbids an administrator from deleting themselves', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $otherUser = User::factory()->create(['role' => UserRole::CASHIER]);

    $this->actingAs($admin);

    expect(Gate::allows('delete', $admin))->toBeFalse()
        ->and(UserResource::canDelete($admin))->toBeFalse()
        ->and(Gate::allows('delete', $otherUser))->toBeTrue()
        ->and(UserResource::canDelete($otherUser))->toBeTrue();

    Livewire::test(EditUser::class, ['record' => $admin->getKey()])
        ->assertActionHidden('delete');
});

it('forbids an administrator from changing their own role', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $otherUser = User::factory()->create(['role' => UserRole::CASHIER]);

    $this->actingAs($admin);

    expect(Gate::allows('changeRole', $admin))->toBeFalse()
        ->and(Gate::allows('changeRole', $otherUser))->toBeTrue();

    $admin->role = UserRole::CASHIER;

    expect(Gate::allows('update', $admin))->toBeFalse();
});

it('forbids non-admin roles from user resource operations', function () {
    $cashier = User::factory()->create(['role' => UserRole::CASHIER]);
    $target = User::factory()->create(['role' => UserRole::CASHIER]);

    $this->actingAs($cashier);

    expect(Gate::allows('viewAny', User::class))->toBeFalse()
        ->and(Gate::allows('create', User::class))->toBeFalse()
        ->and(Gate::allows('update', $target))->toBeFalse()
        ->and(Gate::allows('delete', $target))->toBeFalse()
        ->and(Gate::allows('deleteAny', User::class))->toBeFalse();
});

it('scopes sales query so that a cashier only sees their own sales', function () {
    $cashier1 = User::factory()->create(['role' => UserRole::CASHIER]);
    $cashier2 = User::factory()->create(['role' => UserRole::CASHIER]);

    $sale1 = Sale::factory()->create(['user_id' => $cashier1->id]);
    $sale2 = Sale::factory()->create(['user_id' => $cashier2->id]);

    $this->actingAs($cashier1);

    $visibleSales = SaleResource::getEloquentQuery()->pluck('id')->all();

    expect($visibleSales)->toContain($sale1->id)
        ->and($visibleSales)->not->toContain($sale2->id)
        ->and(Gate::allows('view', $sale1))->toBeTrue()
        ->and(Gate::allows('view', $sale2))->toBeFalse();
});

it('ensures cart and lastSale have the locked attribute in PointOfSale', function () {
    $ref = new ReflectionClass(PointOfSale::class);

    $cartProp = $ref->getProperty('cart');
    $lastSaleProp = $ref->getProperty('lastSale');

    expect($cartProp->getAttributes(Locked::class))->not->toBeEmpty()
        ->and($lastSaleProp->getAttributes(Locked::class))->not->toBeEmpty();
});

it('re-queries product prices from database during PointOfSale checkout', function () {
    $cashier = User::factory()->create(['role' => UserRole::CASHIER]);
    $product = Product::factory()->create(['price' => 50.00, 'is_active' => true]);
    $inventory = Inventory::factory()->for($product)->create(['stock' => 10]);

    $this->actingAs($cashier);

    $component = Livewire::test(PointOfSale::class)
        ->call('addProduct', $inventory->id);

    // Si el precio en BD se actualiza a 60.00 después de agregarlo al carrito:
    $product->update(['price' => 60.00]);

    // Intentar pagar con 50.00 (precio viejo en memoria) debe ser rechazado por monto insuficiente
    $component->set('amountTendered', '50.00')
        ->call('checkout', app(CreateSaleAction::class))
        ->assertNotified('Monto insuficiente');

    expect(Sale::query()->count())->toBe(0);

    // Con el monto correcto de BD (60.00), debe completarse
    $component->set('amountTendered', '60.00')
        ->call('checkout', app(CreateSaleAction::class))
        ->assertNotified('Venta completada');

    expect(Sale::query()->sole()->total)->toBe('60.00')
        ->and($inventory->fresh()->stock)->toBe(9);
});
