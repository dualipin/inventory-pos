<?php

namespace App\Models;

use App\Enums\MovementType;
use Database\Factories\InventoryMovementFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Movimiento de entrada o salida que alimenta el historial digital del inventario.
 */
#[Fillable(['inventory_id', 'type', 'quantity', 'description'])]
class InventoryMovement extends Model
{
    /** @use HasFactory<InventoryMovementFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'type' => MovementType::class,
            'quantity' => 'integer',
        ];
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    protected static function booted(): void
    {
        static::creating(function (self $movement): void {
            $movement->normalizeQuantity();
            $movement->applyStockChange();
        });
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Keeps movement direction consistent with its business meaning.
     *
     * @throws ValidationException
     */
    private function normalizeQuantity(): void
    {
        if (! is_int($this->quantity) || $this->quantity === 0) {
            throw ValidationException::withMessages([
                'quantity' => 'La cantidad debe ser un entero diferente de cero.',
            ]);
        }

        if (! $this->type instanceof MovementType) {
            throw ValidationException::withMessages([
                'type' => 'El tipo de movimiento es obligatorio.',
            ]);
        }

        $this->quantity = match ($this->type) {
            MovementType::Purchase, MovementType::ReturnIn => abs($this->quantity),
            MovementType::Sale, MovementType::Shrinkage => -abs($this->quantity),
            MovementType::Adjustment => $this->quantity,
        };
    }

    /**
     * Valida el movimiento y aplica su delta al stock físico y digital del inventario.
     *
     * @throws ValidationException
     */
    private function applyStockChange(): void
    {
        if ($this->inventory_id === null) {
            throw ValidationException::withMessages([
                'inventory_id' => 'El inventario es obligatorio.',
            ]);
        }

        if (! $this->type instanceof MovementType) {
            throw ValidationException::withMessages([
                'type' => 'El tipo de movimiento es obligatorio.',
            ]);
        }

        DB::transaction(function (): void {
            $inventory = Inventory::query()
                ->whereKey($this->inventory_id)
                ->lockForUpdate()
                ->firstOrFail();

            $stock = $inventory->stock + $this->quantity;

            if ($stock < 0) {
                throw ValidationException::withMessages([
                    'quantity' => "Stock insuficiente: hay {$inventory->stock} unidades disponibles.",
                ]);
            }

            $inventory->update([
                'stock' => $stock,
            ]);
        });
    }
}
