<?php

namespace App\Actions;

use App\Enums\MovementType;
use App\Enums\PaymentMethod;
use App\Enums\SaleStatus;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CreateSaleAction
{
    /**
     * @param  array{payment_method: PaymentMethod|string, items: array<int, array{inventory_id: int|string, quantity: int|string}>}  $data
     */
    public function handle(array $data, User $cashier): Sale
    {
        $items = $this->normalizeItems($data['items'] ?? []);

        return DB::transaction(function () use ($data, $cashier, $items): Sale {
            $inventories = Inventory::query()
                ->with('product')
                ->whereKey(array_keys($items))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($inventories->count() !== count($items)) {
                throw ValidationException::withMessages([
                    'items' => 'Uno o más productos ya no están disponibles.',
                ]);
            }

            $totalCents = 0;

            foreach ($items as $inventoryId => $quantity) {
                $inventory = $inventories->get($inventoryId);

                if ($inventory->product === null || ! $inventory->product->is_active) {
                    throw ValidationException::withMessages([
                        'items' => 'Todos los productos deben estar activos.',
                    ]);
                }

                if ($inventory->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => "Stock insuficiente para {$inventory->product->name}. Disponible: {$inventory->stock}.",
                    ]);
                }

                $totalCents += (int) round(((float) $inventory->product->price) * 100) * $quantity;
            }

            $sale = Sale::create([
                'user_id' => $cashier->id,
                'folio' => $this->generateFolio(),
                'total' => number_format($totalCents / 100, 2, '.', ''),
                'payment_method' => $data['payment_method'] instanceof PaymentMethod
                    ? $data['payment_method']
                    : PaymentMethod::from($data['payment_method']),
                'estatus' => SaleStatus::Completed,
            ]);

            foreach ($items as $inventoryId => $quantity) {
                $inventory = $inventories->get($inventoryId);
                $price = $inventory->product->price;
                $subtotalCents = (int) round(((float) $price) * 100) * $quantity;

                $sale->items()->create([
                    'inventory_id' => $inventoryId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => number_format($subtotalCents / 100, 2, '.', ''),
                ]);

                $movement = new InventoryMovement([
                    'inventory_id' => $inventoryId,
                    'type' => MovementType::Sale,
                    'quantity' => -$quantity,
                    'description' => "Venta {$sale->folio}",
                ]);
                $movement->reference()->associate($sale);
                $movement->save();
            }

            return $sale->load('items.inventory.product', 'user');
        });
    }

    /**
     * @param  array<int, array{inventory_id: int|string, quantity: int|string}>  $items
     * @return array<int, int>
     */
    private function normalizeItems(array $items): array
    {
        if ($items === []) {
            throw ValidationException::withMessages([
                'items' => 'Agrega al menos un producto a la venta.',
            ]);
        }

        $normalized = [];

        foreach ($items as $item) {
            $inventoryId = filter_var($item['inventory_id'] ?? null, FILTER_VALIDATE_INT);
            $quantity = filter_var($item['quantity'] ?? null, FILTER_VALIDATE_INT);

            if ($inventoryId === false || $inventoryId < 1 || $quantity === false || $quantity < 1) {
                throw ValidationException::withMessages([
                    'items' => 'Cada producto debe tener una cantidad entera mayor a cero.',
                ]);
            }

            if (isset($normalized[$inventoryId])) {
                throw ValidationException::withMessages([
                    'items' => 'No repitas el mismo producto en la venta.',
                ]);
            }

            $normalized[$inventoryId] = $quantity;
        }

        return $normalized;
    }

    private function generateFolio(): string
    {
        return 'VTA-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
    }
}
