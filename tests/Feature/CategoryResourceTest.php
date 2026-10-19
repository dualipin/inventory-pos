<?php

use App\Enums\UserRole;
use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Models\Category;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory(['role' => UserRole::ADMIN])->create());
});

it('rejects a category with a name that is already registered', function () {
    Category::factory()->create(['name' => 'Lácteos']);

    Livewire::test(CreateCategory::class)
        ->fillForm([
            'name' => 'Lácteos',
            'description' => 'Duplicado',
        ])
        ->call('create')
        ->assertHasFormErrors(['name']);

    expect(Category::query()->count())->toBe(1);
});

it('shows the description of a category', function () {
    $category = Category::factory()->create(['description' => 'Productos de leche']);

    Livewire::test(ListCategories::class)
        ->assertTableColumnExists('description')
        ->assertTableColumnStateSet('description', 'Productos de leche', $category);
});
