<?php

namespace App\Models;

use Database\Factories\InventoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Existencias de un producto en almacén y en la plataforma web comercial.
 */
#[Fillable(['product_id', 'stock', 'last_counted_at'])]
class Inventory extends Model
{
    /** @use HasFactory<InventoryFactory> */
    use HasFactory;

    protected $attributes = [
        'stock' => 0,
    ];

    protected function casts(): array
    {
        return [
            'stock' => 'integer',
            'last_counted_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }
}
