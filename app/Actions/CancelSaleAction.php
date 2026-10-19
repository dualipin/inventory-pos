<?php

namespace App\Actions;

use App\Enums\MovementType;
use App\Enums\SaleStatus;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CancelSaleAction
{
    /**
     * Cancela una venta y devuelve las unidades al inventario.
     *
     * @throws ValidationException
     */
    public function handle(Sale $sale): Sale
    {
        return DB::transaction(function () use ($sale): Sale {
            $lockedSale = Sale::query()
                ->with('items')
                ->lockForUpdate()
                ->findOrFail($sale->getKey());

            if ($lockedSale->estatus === SaleStatus::Cancelled) {
                throw ValidationException::withMessages([
                    'estatus' => 'La venta ya está cancelada.',
                ]);
            }

            $inventories = Inventory::query()
                ->whereKey($lockedSale->items->pluck('inventory_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($lockedSale->items as $item) {
                $inventory = $inventories->get($item->inventory_id);

                if ($inventory === null) {
                    throw ValidationException::withMessages([
                        'items' => 'El inventario de alguno de los productos ya no existe.',
                    ]);
                }

                $movement = new InventoryMovement([
                    'inventory_id' => $inventory->id,
                    'type' => MovementType::ReturnIn,
                    'quantity' => $item->quantity,
                    'description' => "Cancelación de venta {$lockedSale->folio}",
                ]);
                $movement->reference()->associate($lockedSale);
                $movement->save();
            }

            $lockedSale->update([
                'estatus' => SaleStatus::Cancelled,
            ]);

            return $lockedSale->refresh();
        });
    }
}
