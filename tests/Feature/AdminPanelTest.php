<?php

use App\Enums\UserRole;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\User;

test('guests are redirected to the admin login page', function (string $uri) {
    $this->get($uri)->assertRedirect(route('filament.admin.auth.login'));
})->with([
    '',
    '/categories',
    '/products',
    '/promotions',
    '/inventories',
    '/inventory-movements',
    '/users',
    '/profile',
]);

test('authenticated users can manage users and edit their profile', function () {
    $user = User::factory()->create();

    $user->role = UserRole::ADMIN;

    $this->actingAs($user)
        ->get('/users')
        ->assertOk()
        ->assertSee('Usuarios');

    $this->actingAs($user)
        ->get('/users/create')
        ->assertOk()
        ->assertSee('Correo electrónico');

    $this->actingAs($user)
        ->get('/profile')
        ->assertOk();
});

test('authenticated users can list every inventory resource', function (string $uri) {
    $this->actingAs(User::factory(['role' => UserRole::ADMIN])->create())
        ->get($uri)
        ->assertOk();
})->with([
    '/categories',
    '/products',
    '/promotions',
    '/inventories',
    '/inventory-movements',
    '/users',
]);

test('authenticated users can open every create form', function (string $uri) {
    $this->actingAs(User::factory(['role' => UserRole::ADMIN])->create())
        ->get($uri)
        ->assertOk();
})->with([
    '/categories/create',
    '/products/create',
    '/promotions/create',
    '/inventories/create',
    '/inventory-movements/create',
    '/users/create',
]);

test('an inventory record can only be edited to register a stock count', function () {
    $inventory = Inventory::factory()->create();

    $this->actingAs(User::factory(['role' => UserRole::ADMIN])->create())
        ->get("/inventories/{$inventory->getKey()}/edit")
        ->assertOk();
});

test('a product can be edited from the admin panel', function () {
    $product = Product::factory()->create();

    $this->actingAs(User::factory(['role' => UserRole::ADMIN])->create())
        ->get("/products/{$product->getKey()}/edit")
        ->assertOk();
});

test('a promotion can be edited from the admin panel', function () {
    $promotion = Promotion::factory()->create();

    $this->actingAs(User::factory(['role' => UserRole::ADMIN])->create())
        ->get("/promotions/{$promotion->getKey()}/edit")
        ->assertOk();
});

test('inventory movements are append only', function () {
    $movement = InventoryMovement::factory()->create();

    $this->actingAs(User::factory(['role' => UserRole::ADMIN])->create())
        ->get("/inventory-movements/{$movement->getKey()}/edit")
        ->assertNotFound();
});
