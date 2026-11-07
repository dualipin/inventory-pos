<?php

use App\Enums\UserRole;
use App\Filament\Pages\DesignGuidelines;
use App\Models\User;
use Livewire\Livewire;

test('guests are redirected to the login page from design guidelines', function () {
    $this->get('/design-guidelines')
        ->assertRedirect(route('filament.admin.auth.login'));
});

test('cashiers cannot access the design guidelines page', function () {
    $cashier = User::factory()->create(['role' => UserRole::CASHIER]);

    $this->actingAs($cashier)
        ->get('/design-guidelines')
        ->assertForbidden();
});

test('administrators can view the design guidelines page with live tokens and components', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);

    $this->actingAs($admin)
        ->get('/design-guidelines')
        ->assertOk()
        ->assertSee('The High-Throughput Counter Terminal')
        ->assertSee('1. Indicadores de Estado y Alertas')
        ->assertSee('2. Ergonomía de Búsqueda y Atajos de Teclado')
        ->assertSee('3. Fichas de Producto Canónicas')
        ->assertSee('4. Controles de Ticket y Cantidad')
        ->assertSee('5. Cobro en Efectivo y Cálculo de Cambio en Vivo')
        ->assertSee('6. Tokens de Color y Tipografía Normativa');
});

test('interactive component sandbox handles tender and stock states', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);

    $this->actingAs($admin);

    Livewire::test(DesignGuidelines::class)
        ->assertSet('demoStock', 4)
        ->assertSet('demoQuantity', 2)
        ->call('setDemoTender', 500)
        ->assertSet('demoTender', '500.00')
        ->assertSee('354.50') // Cambio a entregar (500 - 145.50)
        ->call('setExactDemoTender')
        ->assertSet('demoTender', '145.50')
        ->call('toggleDemoStock')
        ->assertSet('demoStock', 24)
        ->call('incrementDemoQuantity')
        ->assertSet('demoQuantity', 3)
        ->call('decrementDemoQuantity')
        ->assertSet('demoQuantity', 2);
});
