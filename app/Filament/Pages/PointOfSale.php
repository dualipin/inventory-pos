<?php

namespace App\Filament\Pages;

use App\Actions\CreateSaleAction;
use App\Enums\PaymentMethod;
use App\Enums\UserRole;
use App\Models\Inventory;
use App\Models\Sale;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
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

    public string $amountTendered = '';

    /**
     * @var array{folio: string, total: float, tendered: float, change: float}|null
     */
    #[Locked]
    public ?array $lastSale = null;

    /**
     * @var array<int, array{inventory_id: int, name: string, price: string, quantity: int, stock: int}>
     */
    #[Locked]
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
        $this->lastSale = null;

        $inventory = $this->products->firstWhere('id', $inventoryId);

        if ($inventory === null && isset($this->cart[$inventoryId])) {
            $inventory = Inventory::query()
                ->with(['product.media'])
                ->whereKey($inventoryId)
                ->whereHas('product', fn ($query) => $query->where('is_active', true))
                ->first();
        }

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

    public function updateQuantity(int $inventoryId, int|string $quantity): void
    {
        if (! isset($this->cart[$inventoryId])) {
            return;
        }

        $qty = max(0, (int) $quantity);

        if ($qty === 0) {
            $this->removeProduct($inventoryId);

            return;
        }

        $stock = $this->cart[$inventoryId]['stock'];

        if ($qty > $stock) {
            $this->cart[$inventoryId]['quantity'] = $stock;

            Notification::make()
                ->warning()
                ->title('Stock máximo alcanzado')
                ->body("Solo hay {$stock} unidades disponibles.")
                ->send();

            return;
        }

        $this->cart[$inventoryId]['quantity'] = $qty;
    }

    public function removeProduct(int $inventoryId): void
    {
        unset($this->cart[$inventoryId]);
    }

    public function clearCart(): void
    {
        $this->cart = [];
        $this->amountTendered = '';
        $this->lastSale = null;
    }

    public function submitSearch(): void
    {
        $trimmedSearch = trim($this->search);

        if ($trimmedSearch === '') {
            return;
        }

        // Try exact barcode match first
        $inventory = Inventory::query()
            ->with(['product.media'])
            ->whereHas('product', function ($query) use ($trimmedSearch): void {
                $query->where('is_active', true)
                    ->where('barcode', $trimmedSearch);
            })
            ->where('stock', '>', 0)
            ->first();

        // If no exact barcode, check if there's only 1 matching product in current filtered collection
        if ($inventory === null && $this->products->count() === 1) {
            $inventory = $this->products->first();
        }

        if ($inventory !== null) {
            $this->addProduct($inventory->id);
            $this->search = '';

            Notification::make()
                ->success()
                ->title('Producto agregado')
                ->body($inventory->product->name)
                ->duration(1800)
                ->send();
        }
    }

    public function setTenderAmount(float|int|string $amount): void
    {
        $this->amountTendered = number_format((float) $amount, 2, '.', '');
    }

    public function setExactTender(): void
    {
        $this->amountTendered = number_format($this->cartTotal, 2, '.', '');
    }

    public function dismissLastSale(): void
    {
        $this->lastSale = null;
    }

    public function checkout(CreateSaleAction $createSaleAction): void
    {
        $this->validate([
            'paymentMethod' => ['required', Rule::enum(PaymentMethod::class)],
            'amountTendered' => ['nullable', 'numeric', 'min:0'],
        ]);

        if ($this->cart === []) {
            Notification::make()
                ->warning()
                ->title('El ticket está vacío')
                ->body('Agrega al menos un producto antes de cobrar.')
                ->send();

            return;
        }

        // Re-consultar precios vigentes y existencias desde la base de datos
        $inventoryIds = array_column($this->cart, 'inventory_id');
        $inventories = Inventory::query()
            ->with('product')
            ->whereIn('id', $inventoryIds)
            ->get()
            ->keyBy('id');

        $totalCents = 0;

        foreach ($this->cart as $item) {
            $inventory = $inventories->get($item['inventory_id']);

            if ($inventory === null || $inventory->product === null || ! $inventory->product->is_active) {
                Notification::make()
                    ->danger()
                    ->title('Producto no disponible')
                    ->body("El producto {$item['name']} ya no está disponible.")
                    ->send();

                return;
            }

            if ($inventory->stock < $item['quantity']) {
                Notification::make()
                    ->warning()
                    ->title('Stock insuficiente')
                    ->body("Solo hay {$inventory->stock} unidades disponibles de {$inventory->product->name}.")
                    ->send();

                return;
            }

            $currentPrice = (float) $inventory->product->price;
            $totalCents += (int) round($currentPrice * 100) * $item['quantity'];
        }

        $calculatedTotal = round($totalCents / 100, 2);
        $tendered = $this->amountTendered !== '' ? (float) $this->amountTendered : $calculatedTotal;

        if ($tendered < $calculatedTotal) {
            Notification::make()
                ->warning()
                ->title('Monto insuficiente')
                ->body('El monto recibido ($'.number_format($tendered, 2).') es menor al total ($'.number_format($calculatedTotal, 2).').')
                ->send();

            return;
        }

        try {
            $sale = DB::transaction(function () use ($createSaleAction): Sale {
                return $createSaleAction->handle([
                    'payment_method' => $this->paymentMethod,
                    'items' => array_values(array_map(
                        fn (array $item): array => [
                            'inventory_id' => $item['inventory_id'],
                            'quantity' => $item['quantity'],
                        ],
                        $this->cart,
                    )),
                ], auth()->user());
            });
        } catch (ValidationException $exception) {
            Notification::make()
                ->danger()
                ->title('No se pudo completar la venta')
                ->body($exception->validator->errors()->first())
                ->send();

            return;
        }

        $saleTotal = (float) $sale->total;
        $change = max(0.0, round($tendered - $saleTotal, 2));

        $this->lastSale = [
            'folio' => $sale->folio,
            'total' => $saleTotal,
            'tendered' => $tendered,
            'change' => $change,
        ];

        $this->cart = [];
        $this->amountTendered = '';
        $this->search = '';

        Notification::make()
            ->success()
            ->title('Venta completada')
            ->body("Folio {$sale->folio} · Cambio $".number_format($change, 2))
            ->send();
    }

    /**
     * @return Collection<int, Inventory>
     */
    #[Computed]
    public function getProductsProperty(): Collection
    {
        return Inventory::query()
            ->select(['id', 'product_id', 'stock'])
            ->with([
                'product:id,category_id,name,price,barcode,is_active',
                'product.category:id,name',
                'product.media',
            ])
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

    public function getChangeDueProperty(): float
    {
        if ($this->amountTendered === '') {
            return 0.0;
        }

        $tendered = (float) $this->amountTendered;

        return max(0.0, round($tendered - $this->cartTotal, 2));
    }

    public function getTenderShortageProperty(): float
    {
        if ($this->amountTendered === '') {
            return 0.0;
        }

        $tendered = (float) $this->amountTendered;

        return max(0.0, round($this->cartTotal - $tendered, 2));
    }
}
