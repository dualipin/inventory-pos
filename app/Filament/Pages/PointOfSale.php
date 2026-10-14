<?php

namespace App\Filament\Pages;

use App\Actions\CreateSaleAction;
use App\Enums\PaymentMethod;
use App\Enums\UserRole;
use App\Models\Inventory;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use UnitEnum;

class PointOfSale extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationLabel = 'Caja';

    protected static ?string $title = 'Punto de venta';

    protected static ?int $navigationSort = -101;

    protected static string|UnitEnum|null $navigationGroup = 'Punto de venta';

    protected string $view = 'filament.pages.point-of-sale';

    protected \Filament\Support\Enums\Width|string|null $maxContentWidth = Width::Full;

    public string $search = '';

    public string $paymentMethod = PaymentMethod::Cash->value;

    /**
     * @var array<int, array{inventory_id: int, name: string, price: string, quantity: int, stock: int}>
     */
    public array $cart = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(UserRole::ADMIN, UserRole::CASHIER) ?? false;
    }

    public function getTitle(): string|Htmlable
    {
        return 'Punto de venta';
    }

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }

    public function getHeading(): string|Htmlable|null
    {
        return null;
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    public function getPageClasses(): array
    {
        return ['pos-page'];
    }

    public function addProduct(int $inventoryId): void
    {
        $inventory = Inventory::query()
            ->with('product')
            ->whereKey($inventoryId)
            ->whereHas('product', fn ($query) => $query->where('is_active', true))
            ->first();

        if ($inventory === null || $inventory->product === null) {
            Notification::make()
                ->danger()
                ->title('Producto no disponible')
                ->body('Actualiza la búsqueda e inténtalo de nuevo.')
                ->send();

            return;
        }

        $currentQuantity = $this->cart[$inventoryId]['quantity'] ?? 0;

        if ($currentQuantity >= $inventory->stock) {
            Notification::make()
                ->warning()
                ->title('Stock máximo alcanzado')
                ->body("Solo hay {$inventory->stock} unidades disponibles.")
                ->send();

            return;
        }

        $this->cart[$inventoryId] = [
            'inventory_id' => $inventory->id,
            'name' => $inventory->product->name,
            'price' => (string) $inventory->product->price,
            'quantity' => $currentQuantity + 1,
            'stock' => $inventory->stock,
        ];
    }

    public function incrementProduct(int $inventoryId): void
    {
        $this->addProduct($inventoryId);
    }

    public function decrementProduct(int $inventoryId): void
    {
        if (! isset($this->cart[$inventoryId])) {
            return;
        }

        if ($this->cart[$inventoryId]['quantity'] === 1) {
            unset($this->cart[$inventoryId]);

            return;
        }

        $this->cart[$inventoryId]['quantity']--;
    }

    public function removeProduct(int $inventoryId): void
    {
        unset($this->cart[$inventoryId]);
    }

    public function clearCart(): void
    {
        $this->cart = [];
    }

    public function checkout(CreateSaleAction $createSaleAction): void
    {
        if ($this->cart === []) {
            Notification::make()
                ->warning()
                ->title('El ticket está vacío')
                ->body('Agrega al menos un producto antes de cobrar.')
                ->send();

            return;
        }

        try {
            $sale = $createSaleAction->handle([
                'payment_method' => $this->paymentMethod,
                'items' => array_values(array_map(
                    fn (array $item): array => [
                        'inventory_id' => $item['inventory_id'],
                        'quantity' => $item['quantity'],
                    ],
                    $this->cart,
                )),
            ], auth()->user());
        } catch (ValidationException $exception) {
            Notification::make()
                ->danger()
                ->title('No se pudo completar la venta')
                ->body($exception->validator->errors()->first())
                ->send();

            return;
        }

        $this->cart = [];
        $this->search = '';

        Notification::make()
            ->success()
            ->title('Venta completada')
            ->body("Folio {$sale->folio} · Total $".number_format((float) $sale->total, 2))
            ->send();
    }

    /**
     * @return Collection<int, Inventory>
     */
    public function getProductsProperty(): Collection
    {
        return Inventory::query()
            ->with(['product.category'])
            ->whereHas('product', function ($query): void {
                $query->where('is_active', true)
                    ->when($this->search !== '', function ($query): void {
                        $query->where(function ($query): void {
                            $query->where('name', 'like', "%{$this->search}%")
                                ->orWhere('barcode', 'like', "%{$this->search}%");
                        });
                    });
            })
            ->where('stock', '>', 0)
            ->orderBy('stock')
            ->orderBy('id')
            ->limit(50)
            ->get();
    }

    public function getCartCountProperty(): int
    {
        return array_sum(array_column($this->cart, 'quantity'));
    }

    public function getCartTotalProperty(): float
    {
        return round(array_reduce(
            $this->cart,
            fn (float $total, array $item): float => $total + ((float) $item['price'] * $item['quantity']),
            0,
        ), 2);
    }
}
