<x-filament-panels::page
    :full-height="true"
    class="[&_.fi-page-header-main-ctn]:h-full! [&_.fi-page-header-main-ctn]:gap-0! [&_.fi-page-header-main-ctn]:py-0! [&_.fi-page-main]:h-full! [&_.fi-page-main]:min-h-0! [&_.fi-page-main]:gap-0! [&_.fi-page-main]:p-0! [&_.fi-page-content]:h-full! [&_.fi-page-content]:min-h-0! [&_.fi-page-content]:gap-0! [&_.fi-page-content]:p-0!"
>
    <div
        x-data="{
            focusSearch() {
                $refs.searchInput?.focus();
                $refs.searchInput?.select();
            }
        }"
        @keydown.window="if ($event.key === '/' && !['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName)) { $event.preventDefault(); focusSearch(); }"
        class="grid h-[calc(100dvh-4.25rem)] min-h-[520px] max-h-[calc(100dvh-4.25rem)] grid-cols-[minmax(0,1fr)_420px] overflow-hidden rounded-xl border border-gray-200 bg-gray-50 shadow-sm dark:border-gray-700 dark:bg-gray-950 max-[1100px]:grid-cols-[minmax(0,1fr)_350px] max-[800px]:block max-[800px]:h-auto max-[800px]:max-h-none max-[800px]:overflow-visible"
    >
        <section class="flex h-full min-w-0 flex-col overflow-hidden p-6 pb-5 max-[800px]:h-auto max-[800px]:overflow-visible max-[800px]:px-4 max-[800px]:py-5">
            <div class="mb-4 flex shrink-0 items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-50">Nueva venta</h1>
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-success-500/20 bg-success-50 px-2 py-0.5 text-xs font-bold text-success-700 dark:bg-success-950/40 dark:text-success-400">
                        <span class="size-1.5 rounded-full bg-success-500"></span>
                        En línea
                    </span>
                </div>
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    <strong class="font-bold text-gray-900 dark:text-gray-100">{{ $this->products->count() }}</strong> productos
                </span>
            </div>

            <div class="relative mb-3 flex shrink-0 items-center">
                <x-filament::icon icon="heroicon-o-magnifying-glass" class="pointer-events-none absolute left-4 size-5 text-gray-400" />
                <input
                    type="search"
                    id="pos-search"
                    x-ref="searchInput"
                    aria-label="Buscar producto o escanear código de barras"
                    wire:model.live.debounce.250ms="search"
                    wire:keydown.enter.prevent="submitSearch"
                    placeholder="Busca por nombre o escanea un código (Enter para agregar)..."
                    class="w-full rounded-xl border border-gray-200 bg-white px-12 py-3 text-sm text-gray-900 shadow-xs outline-none transition placeholder:text-gray-400 focus:border-info-500 focus:ring-3 focus:ring-info-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-50 dark:placeholder:text-gray-500"
                    autofocus
                >
                <div class="absolute right-4 flex items-center gap-1.5">
                    <span wire:loading wire:target="search, submitSearch" class="size-4 animate-spin rounded-full border-2 border-info-500 border-t-transparent"></span>
                    <button
                        type="button"
                        @click="focusSearch()"
                        title="Presiona / para enfocar la búsqueda"
                        class="rounded border border-gray-200 bg-gray-100 px-1.5 py-0.5 text-xs font-semibold text-gray-500 transition hover:bg-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400"
                    >/</button>
                </div>
            </div>

            <div class="pt-2 grid min-h-0 flex-1 grid-cols-[repeat(auto-fill,minmax(240px,1fr))] content-start gap-3 overflow-y-auto pr-1 [scrollbar-color:var(--color-gray-300)_transparent] scrollbar-thin max-[800px]:max-h-[50vh]">
                @forelse ($this->products as $inventory)
                    <button
                        type="button"
                        wire:key="inventory-{{ $inventory->id }}"
                        wire:click="addProduct({{ $inventory->id }})"
                        wire:loading.attr="disabled"
                        wire:target="addProduct({{ $inventory->id }})"
                        class="group relative flex gap-3 rounded-[0.85rem] border border-gray-200 bg-white p-3 text-left shadow-2xs transition hover:-translate-y-0.5 hover:border-info-300 hover:shadow-xs active:translate-y-0 active:scale-[0.99] focus-visible:outline-3 focus-visible:outline-info-500/35 focus-visible:outline-offset-2 dark:border-gray-700 dark:bg-gray-900 dark:hover:border-info-600"
                        title="{{ $inventory->product->name }}"
                    >
                        <span class="grid size-14 shrink-0 place-items-center overflow-hidden rounded-[0.65rem] border border-gray-200 bg-info-50 transition-transform group-hover:scale-[1.02] dark:border-gray-700 dark:bg-info-950/40">
                            @if ($inventory->product->image_url)
                                <img src="{{ $inventory->product->image_url }}" alt="{{ $inventory->product->name }}" loading="lazy" class="size-full object-cover transition-transform duration-200 group-hover:scale-105" />
                            @else
                                <x-filament::icon icon="heroicon-o-cube" class="size-6 text-info-400" />
                            @endif
                        </span>

                        <span class="flex min-w-0 flex-1 flex-col justify-between gap-1">
                            <span class="flex items-center justify-between gap-2 leading-none">
                                <span class="truncate text-[0.6875rem] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ $inventory->product->category?->name ?? 'Producto' }}</span>
                                <span class="@if ($inventory->stock <= 5) text-danger-600 dark:text-danger-400 border-danger-500/30 @else text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700 @endif inline-flex shrink-0 items-center gap-1 rounded-full border bg-gray-50 px-2 py-0.5 text-[0.6875rem] font-semibold dark:bg-gray-800">
                                    <span class="@if ($inventory->stock <= 5) bg-danger-500 @else bg-success-500 @endif size-1.5 rounded-full"></span>
                                    {{ $inventory->stock }} disp.
                                </span>
                            </span>

                            <span class="line-clamp-2 min-h-[2.1rem] wrap-break-word text-sm font-semibold leading-snug text-gray-900 dark:text-gray-50">{{ $inventory->product->name }}</span>

                            <span class="mt-auto flex items-center justify-between gap-2 pt-0.5">
                                <strong class="text-base font-extrabold tracking-tight text-info-600 dark:text-info-400">${{ number_format((float) $inventory->product->price, 2) }}</strong>
                                <span class="grid size-6.5 place-items-center rounded-full border border-info-500/25 bg-info-50 text-info-600 transition group-hover:scale-105 group-hover:bg-info-600 group-hover:text-white dark:bg-info-950/40 dark:text-info-300">
                                    <x-filament::icon icon="heroicon-m-plus" class="size-3.5" />
                                </span>
                            </span>
                        </span>
                    </button>
                @empty
                    <div class="col-span-full flex min-h-62.5 flex-col items-center justify-center gap-2 text-center text-gray-500 dark:text-gray-400">
                        <x-filament::icon icon="heroicon-o-magnifying-glass" class="size-8 text-gray-400" />
                        <strong class="text-sm text-gray-900 dark:text-gray-50">No encontramos productos</strong>
                        <span class="text-xs">Prueba con otro nombre o código de barras.</span>
                    </div>
                @endforelse
            </div>
        </section>

        <aside class="flex h-full max-h-full flex-col overflow-hidden border-l border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-900 max-[800px]:h-auto max-[800px]:max-h-none max-[800px]:border-l-0 max-[800px]:border-t max-[800px]:p-4">
            <div class="flex shrink-0 items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-800">
                <h2 class="text-xl font-bold tracking-tight text-gray-900 dark:text-gray-50">
                    Ticket actual <span class="text-sm font-normal text-gray-400">({{ $this->cartCount }})</span>
                </h2>
                @if ($this->cartCount > 0)
                    <button
                        type="button"
                        wire:click="clearCart"
                        wire:confirm="¿Estás seguro de que deseas vaciar todos los productos del ticket actual?"
                        wire:loading.attr="disabled"
                        wire:target="clearCart"
                        class="rounded px-2 py-1 text-xs font-bold text-danger-600 transition hover:bg-danger-50 dark:text-danger-400 dark:hover:bg-danger-950/40"
                    >Vaciar</button>
                @endif
            </div>

            @if ($lastSale)
                <div class="mt-3 rounded-xl border border-success-500/30 bg-success-50/70 p-3 dark:border-success-500/30 dark:bg-success-950/30">
                    <div class="flex items-center justify-between border-b border-success-200/60 pb-1.5 dark:border-success-800/60">
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-success-700 dark:text-success-400">
                            <x-filament::icon icon="heroicon-m-check-circle" class="size-4" />
                            Folio {{ $lastSale['folio'] }}
                        </span>
                        <button type="button" wire:click="dismissLastSale" class="text-xs font-medium text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">Cerrar</button>
                    </div>
                    <div class="mt-1.5 flex items-baseline justify-between text-xs text-gray-600 dark:text-gray-300">
                        <span>Total: ${{ number_format($lastSale['total'], 2) }} · Recibido: ${{ number_format($lastSale['tendered'], 2) }}</span>
                        <span class="font-extrabold text-success-700 dark:text-success-300">Cambio: ${{ number_format($lastSale['change'], 2) }}</span>
                    </div>
                </div>
            @endif

            <div class="flex min-h-0 flex-1 flex-col gap-2.5 overflow-y-auto py-3 pr-1 [scrollbar-color:var(--color-gray-300)_transparent] scrollbar-thin max-[800px]:max-h-70">
                @forelse ($cart as $item)
                    <div class="grid grid-cols-[1fr_auto] items-center gap-x-2.5 gap-y-1 border-b border-gray-100 pb-2.5 last:border-b-0 last:pb-0 dark:border-gray-800" wire:key="cart-{{ $item['inventory_id'] }}">
                        <div class="flex min-w-0 flex-col gap-0.5">
                            <strong class="truncate text-xs font-semibold text-gray-900 dark:text-gray-50">{{ $item['name'] }}</strong>
                            <span class="text-[0.6875rem] text-gray-500 dark:text-gray-400">${{ number_format((float) $item['price'], 2) }} c/u</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <button
                                type="button"
                                wire:click="decrementProduct({{ $item['inventory_id'] }})"
                                wire:loading.attr="disabled"
                                wire:target="decrementProduct({{ $item['inventory_id'] }})"
                                aria-label="Disminuir cantidad de {{ $item['name'] }}"
                                class="grid size-6 place-items-center rounded border border-gray-200 bg-gray-50 text-base leading-none text-gray-900 transition hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-50 dark:hover:bg-gray-700"
                            >−</button>
                            <input
                                type="number"
                                min="1"
                                max="{{ $item['stock'] }}"
                                value="{{ $item['quantity'] }}"
                                wire:change="updateQuantity({{ $item['inventory_id'] }}, $event.target.value)"
                                aria-label="Cantidad para {{ $item['name'] }}"
                                class="w-11 rounded border border-gray-200 bg-white py-0.5 text-center text-xs font-bold text-gray-900 shadow-2xs outline-none focus:border-info-500 focus:ring-2 focus:ring-info-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-50 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            >
                            <button
                                type="button"
                                wire:click="incrementProduct({{ $item['inventory_id'] }})"
                                wire:loading.attr="disabled"
                                wire:target="incrementProduct({{ $item['inventory_id'] }})"
                                aria-label="Aumentar cantidad de {{ $item['name'] }}"
                                class="grid size-6 place-items-center rounded border border-gray-200 bg-gray-50 text-base leading-none text-gray-900 transition hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-50 dark:hover:bg-gray-700"
                            >+</button>
                        </div>
                        <strong class="text-right text-xs text-gray-900 dark:text-gray-50">${{ number_format((float) $item['price'] * $item['quantity'], 2) }}</strong>
                        <button
                            type="button"
                            wire:click="removeProduct({{ $item['inventory_id'] }})"
                            wire:loading.attr="disabled"
                            wire:target="removeProduct({{ $item['inventory_id'] }})"
                            class="col-start-2 justify-self-end p-0.5 text-gray-400 transition hover:text-danger-600 dark:hover:text-danger-400"
                            aria-label="Quitar {{ $item['name'] }} del ticket"
                        >
                            <x-filament::icon icon="heroicon-m-x-mark" class="size-3.5" />
                        </button>
                    </div>
                @empty
                    <div class="flex h-full min-h-45 flex-col items-center justify-center gap-2 text-center text-gray-500 dark:text-gray-400">
                        <span class="mb-1.5 grid size-12 place-items-center rounded-full border border-gray-200 bg-gray-100 text-gray-400 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-500">
                            <x-filament::icon icon="heroicon-o-shopping-bag" class="size-5.5" />
                        </span>
                        <strong class="text-sm text-gray-900 dark:text-gray-50">El ticket está vacío</strong>
                        <span class="text-xs">Agrega productos o escanea un código.</span>
                    </div>
                @endforelse
            </div>

            <!-- PAYMENT SUMMARY & INTEGRATED TENDER -->
            <div class="border-t border-gray-200 pt-3 dark:border-gray-700">
                <div class="flex items-baseline justify-between pb-2.5">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Total a cobrar</span>
                    </div>
                    <strong class="text-[1.85rem] font-extrabold tracking-[-0.045em] text-info-600 dark:text-info-400">
                        ${{ number_format($this->cartTotal, 2) }}
                    </strong>
                </div>

                @if ($this->cartCount > 0)
                    <div class="mb-3 space-y-2 rounded-xl border border-gray-200 bg-gray-50 p-2.5 dark:border-gray-700 dark:bg-gray-800/40">
                        <!-- Quick Cash Pills -->
                        <div class="grid grid-cols-5 gap-1 text-center">
                            @foreach ([50, 100, 200, 500] as $bill)
                                <button
                                    type="button"
                                    wire:click="setTenderAmount({{ $bill }})"
                                    class="rounded-lg border border-gray-200 bg-white py-1.5 text-xs font-bold text-gray-700 shadow-2xs transition hover:border-info-400 hover:bg-info-50 active:scale-95 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200"
                                >${{ $bill }}</button>
                            @endforeach
                            <button
                                type="button"
                                wire:click="setExactTender"
                                title="Pagar monto exacto"
                                class="rounded-lg border border-info-300 bg-info-50 py-1.5 text-xs font-bold text-info-700 shadow-2xs transition hover:bg-info-100 active:scale-95 dark:border-info-700/60 dark:bg-info-950/60 dark:text-info-300"
                            >Exacto</button>
                        </div>

                        <!-- Tender Input + Live Result in 1 Compact Row -->
                        <div class="grid grid-cols-[1fr_auto] items-center gap-2">
                            <div class="relative">
                                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">$</span>
                                <input
                                    type="number"
                                    step="any"
                                    min="0"
                                    wire:model.live.debounce.150ms="amountTendered"
                                    placeholder="Recibido..."
                                    aria-label="Monto recibido en efectivo"
                                    class="w-full rounded-lg border border-gray-200 bg-white py-1.5 pl-6 pr-2 text-xs font-bold text-gray-900 shadow-2xs outline-none focus:border-info-500 focus:ring-2 focus:ring-info-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-50"
                                >
                            </div>
                            <div class="text-right">
                                @if ($amountTendered !== '')
                                    @if ((float) $amountTendered >= $this->cartTotal)
                                        <div class="leading-tight">
                                            <span class="block text-[0.6875rem] font-semibold text-gray-500">Cambio</span>
                                            <strong class="text-sm font-extrabold text-success-600 dark:text-success-400">
                                                ${{ number_format($this->changeDue, 2) }}
                                            </strong>
                                        </div>
                                    @else
                                        <div class="leading-tight">
                                            <span class="block text-[0.6875rem] font-semibold text-danger-500">Faltan</span>
                                            <strong class="text-xs font-bold text-danger-600 dark:text-danger-400">
                                                ${{ number_format($this->tenderShortage, 2) }}
                                            </strong>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400 dark:text-gray-500">Efectivo</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <button
                    type="button"
                    wire:click="checkout"
                    wire:loading.attr="disabled"
                    wire:target="checkout"
                    @disabled($this->cartCount === 0 || ($amountTendered !== '' && (float) $amountTendered < $this->cartTotal))
                    class="flex w-full shrink-0 items-center justify-center gap-2 rounded-lg bg-info-600 px-4 py-3.5 text-sm font-extrabold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-info-700 focus-visible:outline-3 focus-visible:outline-info-500/35 disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:translate-y-0 disabled:hover:bg-info-600"
                >
                    <span wire:loading.remove wire:target="checkout">
                        @if ($this->cartCount === 0)
                            Ticket vacío
                        @else
                            Cobrar ${{ number_format($this->cartTotal, 2) }}
                        @endif
                    </span>
                    <span wire:loading wire:target="checkout">Procesando cobro...</span>
                    <x-filament::icon icon="heroicon-m-arrow-right" wire:loading.remove wire:target="checkout" class="size-4" />
                </button>
            </div>
        </aside>
    </div>
</x-filament-panels::page>
