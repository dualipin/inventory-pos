<?php

use App\Enums\UserRole;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Models\Category;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\User;
use Livewire\Livewire;

it('hides the delete action of a product for the inventory role', function () {
    $product = Product::factory()->create();

    $this->actingAs(User::factory(['role' => UserRole::INVENTORY])->create());

    Livewire::test(EditProduct::class, ['record' => $product->getKey()])
        ->assertActionHidden('delete');

    expect(Product::query()->count())->toBe(1);
});

it('refuses to bulk delete products for the inventory role', function () {
    $products = Product::factory()->count(2)->create();

    $this->actingAs(User::factory(['role' => UserRole::INVENTORY])->create());

    Livewire::test(ListProducts::class)
        ->selectTableRecords($products)
        ->callTableBulkAction('delete', records: $products);

    expect(Product::query()->count())->toBe(2);
});

it('bulk deletes products for an administrator', function () {
    $products = Product::factory()->count(2)->create();

    $this->actingAs(User::factory(['role' => UserRole::ADMIN])->create());

    Livewire::test(ListProducts::class)
        ->selectTableRecords($products)
        ->callTableBulkAction('delete', records: $products);

    expect(Product::query()->count())->toBe(0);
});

it('hides the delete action of a category for the inventory role', function () {
    $category = Category::factory()->create();

    $this->actingAs(User::factory(['role' => UserRole::INVENTORY])->create());

    Livewire::test(EditCategory::class, ['record' => $category->getKey()])
        ->assertActionHidden('delete');

    expect(Category::query()->count())->toBe(1);
});

it('forbids the inventory role from reaching a promotion', function () {
    $promotion = Promotion::factory()->create();

    $this->actingAs(User::factory(['role' => UserRole::INVENTORY])->create())
        ->get("/promotions/{$promotion->getKey()}/edit")
        ->assertForbidden();

    expect(Promotion::query()->count())->toBe(1);
});
