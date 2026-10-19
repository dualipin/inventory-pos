<?php

namespace App\Livewire;

use App\Enums\ProductPackaging;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProductCatalogue extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'categoria')]
    public ?int $selectedCategory = null;

    #[Url(as: 'disponible')]
    public bool $onlyInStock = false;

    #[Url(as: 'orden')]
    public string $sortBy = 'name_asc';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory(): void
    {
        $this->resetPage();
    }

    public function updatingOnlyInStock(): void
    {
        $this->resetPage();
    }

    public function updatingSortBy(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'selectedCategory', 'onlyInStock', 'sortBy']);
        $this->resetPage();
    }

    public function selectCategory(?int $categoryId): void
    {
        $this->selectedCategory = $categoryId;
        $this->resetPage();
    }

    public function render(): View
    {
        $categories = Category::query()
            ->withCount('products')
            ->orderBy('name')
            ->get();

        $query = Product::query()
            ->with(['category', 'inventory'])
            ->where('is_active', true)
            ->when($this->search !== '', function ($q) {
                $term = '%'.$this->search.'%';
                $packagings = $this->matchingPackagings();

                $q->where(function ($sub) use ($term, $packagings) {
                    $sub->where('name', 'like', $term)
                        ->orWhere('presentation', 'like', $term)
                        ->orWhere('size', 'like', $term)
                        ->orWhere('barcode', 'like', $term);

                    if ($packagings !== []) {
                        $sub->orWhereIn('packaging', $packagings);
                    }
                });
            })
            ->when($this->selectedCategory, function ($q) {
                $q->where('category_id', $this->selectedCategory);
            })
            ->when($this->onlyInStock, function ($q) {
                $q->whereHas('inventory', function ($inv) {
                    $inv->where('stock', '>', 0);
                });
            });

        $query = match ($this->sortBy) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'newest' => $query->latest('id'),
            default => $query->orderBy('name', 'asc'),
        };

        $products = $query->paginate(9);

        return view('livewire.product-catalogue', [
            'products' => $products,
            'categories' => $categories,
            'totalCount' => Product::query()->where('is_active', true)->count(),
        ]);
    }

    /**
     * Empaques cuyo valor o etiqueta coinciden con la búsqueda.
     *
     * @return array<int, string>
     */
    private function matchingPackagings(): array
    {
        $search = mb_strtolower(trim($this->search));

        if ($search === '') {
            return [];
        }

        return collect(ProductPackaging::cases())
            ->filter(fn (ProductPackaging $packaging): bool => str_contains($packaging->value, $search)
                || str_contains(mb_strtolower($packaging->getLabel()), $search))
            ->pluck('value')
            ->all();
    }
}
