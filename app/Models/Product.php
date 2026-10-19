<?php

namespace App\Models;

use App\Enums\ProductPackaging;
use App\Enums\ProductUnit;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Producto comercial.
 */
#[Fillable(['category_id', 'name', 'price', 'cost', 'packaging', 'presentation', 'size', 'barcode', 'unit', 'is_active'])]
class Product extends Model implements HasMedia
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected function casts(): array
    {
        return [
            'barcode' => 'string',
            'price' => 'decimal:2',
            'cost' => 'decimal:2',
            'packaging' => ProductPackaging::class,
            'presentation' => 'string',
            'size' => 'string',
            'unit' => ProductUnit::class,
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function inventory(): HasOne
    {
        return $this->hasOne(Inventory::class);
    }

    public function promotions(): BelongsToMany
    {
        return $this->belongsToMany(Promotion::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(400)
            ->height(400)
            ->nonQueued();
    }

    public function ensureInventory(): Inventory
    {
        return $this->inventory()->firstOrCreate();
    }

    public function getImageUrlAttribute(): ?string
    {
        if ($this->hasMedia('image')) {
            return $this->getFirstMediaUrl('image');
        }

        return null;
    }
}
