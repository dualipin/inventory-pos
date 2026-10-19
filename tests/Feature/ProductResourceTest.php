<?php

use App\Enums\ProductPackaging;
use App\Enums\ProductUnit;
use App\Enums\UserRole;
use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory(['role' => UserRole::ADMIN])->create());
});

it('creates a product with every column the products table requires', function () {
    $category = Category::factory()->create();

    Livewire::test(CreateProduct::class)
        ->fillForm([
            'category_id' => $category->id,
            'name' => 'Leche Entera LALA',
            'barcode' => '7501234567890',
            'price' => 25.50,
            'cost' => 18.20,
            'packaging' => ProductPackaging::Bottle->value,
            'unit' => ProductUnit::Liter->value,
            'presentation' => '1 L',
            'size' => '1000',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $product = Product::query()->sole();

    expect($product->barcode)->toBe('7501234567890')
        ->and($product->cost)->toBe('18.20')
        ->and($product->packaging)->toBe(ProductPackaging::Bottle)
        ->and($product->unit)->toBe(ProductUnit::Liter)
        ->and($product->is_active)->toBeTrue()
        ->and($product->inventory)->not->toBeNull();
});

it('rejects a product with a barcode that is already registered', function () {
    Product::factory()->create(['barcode' => '7501234567890']);
    $category = Category::factory()->create();

    Livewire::test(CreateProduct::class)
        ->fillForm([
            'category_id' => $category->id,
            'name' => 'Leche Entera LALA',
            'barcode' => '7501234567890',
            'price' => 25.50,
            'cost' => 18.20,
            'packaging' => ProductPackaging::Bottle->value,
            'unit' => ProductUnit::Liter->value,
            'presentation' => '1 L',
            'size' => '1000',
        ])
        ->call('create')
        ->assertHasFormErrors(['barcode']);

    expect(Product::query()->count())->toBe(1);
});

it('deactivates a product so that it leaves the point of sale', function () {
    $product = Product::factory()->create(['is_active' => true]);

    Livewire::test(EditProduct::class, ['record' => $product->getKey()])
        ->fillForm(['is_active' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($product->fresh()->is_active)->toBeFalse();
});

it('keeps a deleted product out of the catalogue', function () {
    $product = Product::factory()->create(['name' => 'Yogurt Natural']);

    Livewire::test(EditProduct::class, ['record' => $product->getKey()])
        ->callAction('delete');

    expect(Product::query()->count())->toBe(0)
        ->and(Product::withTrashed()->count())->toBe(1)
        ->and(Product::withTrashed()->sole()->trashed())->toBeTrue();
});

it('restores a deleted product from the products table', function () {
    $product = Product::factory()->create();
    $product->delete();

    Livewire::test(ListProducts::class)
        ->callTableAction('restore', $product);

    expect(Product::query()->count())->toBe(1)
        ->and(Product::withTrashed()->count())->toBe(1);
});

it('offers the force delete action only for a deleted product', function () {
    $product = Product::factory()->create();
    $deletedProduct = Product::factory()->create();
    $deletedProduct->delete();

    Livewire::test(ListProducts::class)
        ->assertTableActionHidden('forceDelete', $product)
        ->assertTableActionVisible('forceDelete', $deletedProduct);
});

it('hides the force delete action for the inventory role', function () {
    $product = Product::factory()->create();
    $product->delete();

    $this->actingAs(User::factory(['role' => UserRole::INVENTORY])->create());

    Livewire::test(ListProducts::class)
        ->assertTableActionHidden('forceDelete', $product);
});

it('shows the barcode cost unit and active state in the products table', function () {
    $product = Product::factory()->create([
        'name' => 'Yogurt Natural',
        'barcode' => '7509998887776',
        'cost' => 15.75,
    ]);

    Livewire::test(ListProducts::class)
        ->assertTableColumnExists('barcode')
        ->assertTableColumnExists('cost')
        ->assertTableColumnExists('unit')
        ->assertTableColumnExists('is_active')
        ->assertTableColumnStateSet('barcode', '7509998887776', $product)
        ->assertTableColumnStateSet('cost', '15.75', $product)
        ->assertTableColumnStateSet('unit', $product->unit, $product);
});
