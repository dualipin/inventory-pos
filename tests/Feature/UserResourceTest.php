<?php

use App\Enums\UserRole;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory(['role' => UserRole::ADMIN])->create());
});

it('shows the responsibility of every user', function () {
    $cashier = User::factory()->create(['role' => UserRole::CASHIER]);

    Livewire::test(ListUsers::class)
        ->assertTableColumnExists('role')
        ->assertTableColumnStateSet('role', UserRole::CASHIER, $cashier);
});
