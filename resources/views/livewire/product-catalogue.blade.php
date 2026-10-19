<div class="py-8 lg:py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header del Catálogo -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-6 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="badge badge-primary badge-sm font-semibold text-white">Catálogo Mayorista y Detalle</span>
                    <span class="text-xs text-slate-500 font-medium">CEDIS Atasta de Serra</span>
                </div>
                <h1 class="font-display font-extrabold text-3xl sm:text-4xl text-secondary tracking-tight">
                    Catálogo de Productos LALA
                </h1>
                <p class="text-slate-600 text-sm sm:text-base mt-1 max-w-2xl">
                    Consulta en tiempo real nuestro portafolio de productos lácteos, bebidas y cárnicos, con información de presentaciones, precios sugeridos y existencias de almacén.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs text-slate-500">Mostrando <strong class="text-secondary">{{ $products->total() }}</strong> de {{ $totalCount }} productos</span>
            </div>
        </div>

        <!-- Categorías Pills -->
        <div class="flex items-center gap-2 overflow-x-auto py-4 scrollbar-none">
            <button 
                wire:click="selectCategory(null)" 
                class="btn btn-sm rounded-full transition-all duration-200 shrink-0 {{ is_null($selectedCategory) ? 'btn-primary text-white shadow-sm' : 'btn-outline border-slate-300 text-slate-700 hover:bg-slate-100 hover:border-slate-400' }}">
                Todas las Familias ({{ $totalCount }})
            </button>
            @foreach($categories as $category)
                <button 
                    wire:click="selectCategory({{ $category->id }})" 
                    class="btn btn-sm rounded-full transition-all duration-200 shrink-0 {{ $selectedCategory === $category->id ? 'btn-primary text-white shadow-sm' : 'btn-outline border-slate-300 text-slate-700 hover:bg-slate-100 hover:border-slate-400' }}">
                    {{ $category->name }} ({{ $category->products_count }})
                </button>
            @endforeach
        </div>
    </div>

    <!-- Barra de Filtros y Búsqueda -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-5 mb-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4 items-center">
            <!-- Input de Búsqueda -->
            <div class="lg:col-span-5 relative">
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Buscar por producto, presentación o empaque..." 
                        class="input input-bordered w-full pl-10 pr-10 rounded-xl bg-slate-50 focus:bg-white border-slate-300 text-sm focus:border-primary focus:outline-none"
                    />
                    <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    
                    @if($search !== '')
                        <button wire:click="$set('search', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Selector de Categoría (Dropdown alternativo) -->
            <div class="lg:col-span-3">
                <select wire:model.live="selectedCategory" class="select select-bordered w-full rounded-xl bg-slate-50 text-sm border-slate-300 focus:border-primary focus:outline-none">
                    <option value="">Todas las Familias</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Selector de Orden -->
            <div class="lg:col-span-2">
                <select wire:model.live="sortBy" class="select select-bordered w-full rounded-xl bg-slate-50 text-sm border-slate-300 focus:border-primary focus:outline-none">
                    <option value="name_asc">Nombre (A - Z)</option>
                    <option value="price_asc">Precio: Menor a Mayor</option>
                    <option value="price_desc">Precio: Mayor a Menor</option>
                    <option value="newest">Más recientes</option>
                </select>
            </div>

            <!-- Switch de Solo en Stock -->
            <div class="lg:col-span-2 flex items-center justify-between lg:justify-end gap-2 px-1">
                <label class="label cursor-pointer gap-2 py-0">
                    <span class="label-text text-xs font-semibold text-slate-700">Solo con Stock</span>
                    <input type="checkbox" wire:model.live="onlyInStock" class="toggle toggle-primary toggle-sm" />
                </label>
            </div>
        </div>

        <!-- Indicador de carga & Filtros Activos -->
        <div class="mt-3 pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-xs">
            <div class="flex items-center gap-2 text-slate-500">
                <div wire:loading class="flex items-center gap-1.5 text-primary font-medium">
                    <span class="loading loading-spinner loading-xs"></span>
                    <span>Actualizando catálogo...</span>
                </div>
                <div wire:loading.remove>
                    <span>Mostrando resultados</span>
                </div>
            </div>

            @if($search !== '' || !is_null($selectedCategory) || $onlyInStock || $sortBy !== 'name_asc')
                <button wire:click="resetFilters" class="btn btn-ghost btn-xs text-primary hover:bg-primary/10 rounded-lg">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Restablecer filtros
                </button>
            @endif
        </div>
    </div>

    <!-- Grid de Productos -->
    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 gsap-stagger-container">
            @foreach($products as $product)
                @php
                    $stock = $product->inventory?->stock ?? 0;
                    $hasStock = $stock > 0;
                @endphp
                <div class="card bg-white rounded-3xl shadow-sm hover:shadow-md transition-all duration-300 border border-slate-200 overflow-hidden flex flex-col gsap-stagger-item group">
                    <!-- Imagen de Producto & Badges -->
                    <div class="relative h-56 bg-gradient-to-b from-slate-50 to-slate-100 flex items-center justify-center p-6 overflow-hidden">
                        <img 
                            src="{{ $product->image_url }}" 
                            alt="{{ $product->name }}" 
                            class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-300"
                            loading="lazy"
                        />
                        
                        <!-- Badge de Categoría -->
                        <span class="badge badge-sm badge-neutral absolute top-4 left-4 bg-secondary/90 text-white font-medium shadow-sm">
                            {{ $product->category?->name ?? 'General' }}
                        </span>

                        <!-- Badge de Stock en Almacén -->
                        <div class="absolute top-4 right-4">
                            @if($hasStock)
                                <span class="badge badge-sm badge-success text-white font-medium shadow-sm flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                    Disponible
                                </span>
                            @else
                                <span class="badge badge-sm badge-error text-white font-medium shadow-sm">
                                    Agotado
                                </span>
                            @endif
                        </div>

                        <!-- Indicador de disponibilidad del producto -->
                        @if($product->is_active)
                            <div class="absolute bottom-3 left-4">
                                <span class="badge badge-xs bg-emerald-500 text-white font-bold py-1 px-2 rounded-full shadow-sm flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Producto disponible
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Contenido y Datos Técnicos -->
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <!-- Nombre y presentación -->
                            <div class="mb-3">
                                <h3 class="font-display font-bold text-lg text-secondary group-hover:text-primary transition-colors line-clamp-1">
                                    {{ $product->name }}
                                </h3>
                                <p class="text-xs text-slate-500 font-medium">
                                    Presentación: <span class="text-slate-700 font-semibold">{{ $product->presentation ?? 'Estándar' }}</span> • Tamaño: <span class="text-slate-700 font-semibold">{{ $product->size }}</span>
                                </p>
                            </div>

                            <!-- Ficha de Empaque & Inventario -->
                            <div class="bg-slate-50 rounded-2xl p-3 mb-4 border border-slate-100 text-xs space-y-1.5">
                                <div class="flex items-center justify-between text-slate-600">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                        Empaque:
                                    </span>
                                    <span class="font-semibold text-slate-800">{{ $product->packaging?->getLabel() ?? 'Sin especificar' }}</span>
                                </div>
                                <div class="flex items-center justify-between text-slate-600">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Existencias (CEDIS):
                                    </span>
                                    <span class="font-bold {{ $stock > 20 ? 'text-emerald-700' : ($stock > 0 ? 'text-amber-600' : 'text-red-500') }}">
                                        {{ number_format($stock) }} uds
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Precio y Acción -->
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                            <div>
                                <span class="text-[11px] text-slate-400 block font-medium uppercase tracking-wider">Precio Sugerido</span>
                                <span class="font-display font-extrabold text-2xl text-primary leading-tight">
                                    ${{ number_format($product->price, 2) }}
                                </span>
                                <span class="text-[10px] text-slate-400 block">MXN IVA incluido</span>
                            </div>

                            <a href="tel:9933541200" class="btn btn-sm btn-outline border-primary text-primary hover:bg-primary hover:text-white rounded-xl font-medium gap-1.5 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <span>Pedir</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="mt-10">
            {{ $products->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm max-w-xl mx-auto my-8">
            <div class="w-16 h-16 rounded-full bg-primary/10 text-primary flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="font-display font-bold text-xl text-secondary mb-2">No se encontraron productos</h3>
            <p class="text-sm text-slate-500 mb-6">
                No hay productos que coincidan con los criterios de búsqueda o filtros seleccionados.
            </p>
            <button wire:click="resetFilters" class="btn btn-primary rounded-xl text-white font-medium">
                Limpiar búsqueda y filtros
            </button>
        </div>
    @endif
</div>
