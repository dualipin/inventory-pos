<?php

use App\Enums\DiscountType;
use App\Enums\UserRole;
use App\Filament\Resources\Promotions\Pages\ListPromotions;
use App\Models\Promotion;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory(['role' => UserRole::ADMIN])->create());
});

it('shows the description of a promotion', function () {
    $promotion = Promotion::factory()->create(['description' => 'Dos por uno en yogures']);

    Livewire::test(ListPromotions::class)
        ->assertTableColumnExists('description')
        ->assertTableColumnStateSet('description', 'Dos por uno en yogures', $promotion);
});

it('shows the discount type of a promotion', function () {
    $promotion = Promotion::factory()->percent()->create(['discount_value' => 25]);

    Livewire::test(ListPromotions::class)
        ->assertTableColumnStateSet('discount_type', '25%', $promotion);
});

it('knows that a percent discount is not a fixed amount', function () {
    expect(DiscountType::Percent->getLabel())->toBe('Porcentaje')
        ->and(DiscountType::Fixed->getLabel())->toBe('Monto fijo');
});
