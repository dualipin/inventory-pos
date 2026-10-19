<x-filament-panels::page
    :full-height="true"
    class="[&_.fi-page-header-main-ctn]:h-full! [&_.fi-page-header-main-ctn]:gap-0! [&_.fi-page-header-main-ctn]:py-0! [&_.fi-page-main]:h-full! [&_.fi-page-main]:min-h-0! [&_.fi-page-main]:gap-0! [&_.fi-page-main]:p-0! [&_.fi-page-content]:h-full! [&_.fi-page-content]:min-h-0! [&_.fi-page-content]:gap-0! [&_.fi-page-content]:p-0!"
>
    <div class="grid h-[calc(100dvh-4.25rem)] min
    -h-[520px] max-h-[calc(100dvh-4.25rem)] grid-cols-[minmax(0,1fr)_420px] overflow-hidden rounded-xl border border-gray-200 bg-gray-50 shadow-sm dark:border-gray-700 dark:bg-gray-950 max-[1100px]:grid-cols-[minmax(0,1fr)_350px] max-[800px]:block max-[800px]:h-auto max-[800px]:max-h-none max-[800px]:overflow-visible">
        <section class="flex h-full min-w-0 flex-col overflow-hidden p-6 pb-5 max-[800px]:h-auto max-[800px]:overflow-visible max-[800px]:px-4 max-[800px]:py-5">
            <div class="mb-5 flex shrink-0 items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Caja abierta</p>
                    <h1 class="text-[1.6rem] font-bold tracking-[-0.035em] text-gray-900 dark:text-gray-50">Nueva venta</h1>
                </div>
                <div class="inline-flex items-center gap-2 text-xs font-bold text-success-600 dark:text-success-400">
                    <span class="size-2 rounded-full bg-success-500 ring-4 ring-success-500/20"></span>
                    En línea
                </div>
            </div>

            <div class="relative mb-3.5 flex shrink-0 items-center">
                <x-filament::icon icon="heroicon-o-magnifying-glass" class="pointer-events-none absolute left-4 size-5 text-gray-400" />
                <input
                    type="search"
                    wire:model.live.debounce.250ms="search"
                    placeholder="Busca por nombre o escanea un código..."
                    class="w-full rounded-xl border border-gray-200 bg-white px-12 py-3.5 text-sm text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-info-500 focus:ring-4 focus:ring-info-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-50 dark:placeholder:text-gray-500"
                    autofocus
                >
                <kbd class="absolute right-4 rounded border border-gray-200 bg-white px-1.5 py-0.5 text-xs text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">/</kbd>
            </div>

            <div class="mb-3.5 flex shrink-0 items-center justify-between text-xs font-bold text-gray-700 dark:text-gray-300">
                <span>{{ $this->products->count() }} productos disponibles</span>
                <span class="font-medium text-gray-500 dark:text-gray-400">Selecciona un producto para agregarlo</span>
            </div>

            <div class="pt-5 grid min-h-0 flex-1 grid-cols-[repeat(auto-fill,minmax(240px,1fr))] content-start gap-3.5 overflow-y-auto pr-1 [scrollbar-color:var(--color-gray-300)_transparent] scrollbar-thin max-[800px]:max-h-[50vh]">
                @forelse ($this->products as $inventory)
                    <button
                        type="button"
                        wire:key="inventory-{{ $inventory->id }}"
                        wire:click="addProduct({{ $inventory->id }})"
                        wire:loading.attr="disabled"
                        wire:target="addProduct"
                        class="group relative flex gap-3.5 rounded-[0.85rem] border border-gray-200 bg-white p-3.5 text-left shadow-sm transition hover:-translate-y-0.5 hover:border-info-300 hover:shadow-md active:translate-y-0 active:scale-[0.99] focus-visible:outline-3 focus-visible:outline-info-500/35 focus-visible:outline-offset-2 dark:border-gray-700 dark:bg-gray-900 dark:hover:border-info-600"
                        title="{{ $inventory->product->name }}"
                    >
                        <span class="grid size-15 shrink-0 place-items-center overflow-hidden rounded-[0.65rem] border border-gray-200 bg-info-50 transition-transform group-hover:scale-[1.02] dark:border-gray-700 dark:bg-info-950/40">
                            @if ($inventory->product->image_url)
                                <img src="{{ $inventory->product->image_url }}" alt="{{ $inventory->product->name }}" loading="lazy" class="size-full object-cover transition-transform duration-200 group-hover:scale-105" />
                            @else
                                <x-filament::icon icon="heroicon-o-cube" class="size-7 text-info-400" />
                            @endif
                        </span>

                        <span class="flex min-w-0 flex-1 flex-col justify-between gap-1.5">
                            <span class="flex items-center justify-between gap-2 leading-none">
                                <span class="truncate text-[0.65rem] font-bold uppercase tracking-[0.04em] text-gray-500 dark:text-gray-400">{{ $inventory->product->category?->name ?? 'Producto' }}</span>
                                <span class="@if ($inventory->stock <= 5) text-danger-600 dark:text-danger-400 border-danger-500/30 @else text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700 @endif inline-flex shrink-0 items-center gap-1 rounded-full border bg-gray-50 px-2 py-0.5 text-[0.68rem] font-semibold dark:bg-gray-800">
                                    <span class="@if ($inventory->stock <= 5) bg-danger-500 @else bg-success-500 @endif size-1.5 rounded-full"></span>
                                    {{ $inventory->stock }} disp.
                                </span>
                            </span>

                            <span class="line-clamp-2 min-h-[2.1rem] wrap-break-word text-[0.84rem] font-bold leading-tight text-gray-900 dark:text-gray-50">{{ $inventory->product->name }}</span>

                            <span class="mt-auto flex items-center justify-between gap-2 pt-0.5">
                                <strong class="text-[1.05rem] font-extrabold tracking-tight text-info-600 dark:text-info-400">${{ number_format((float) $inventory->product->price, 2) }}</strong>
                                <span class="grid size-7 place-items-center rounded-full border border-info-500/25 bg-info-50 text-info-600 transition group-hover:scale-105 group-hover:bg-info-600 group-hover:text-white dark:bg-info-950/40 dark:text-info-300">
                                    <x-filament::icon icon="heroicon-m-plus" class="size-3.5" />
                                </span>
                            </span>
                        </span>
                    </button>
                @empty
                    <div class="col-span-full flex min-h-62.5 flex-col items-center justify-center gap-2 text-center text-gray-500 dark:text-gray-400">
                        <x-filament::icon icon="heroicon-o-magnifying-glass" class="size-8" />
                        <strong class="text-sm text-gray-900 dark:text-gray-50">No encontramos productos</strong>
                        <span class="text-xs">Prueba con otro nombre o código de barras.</span>
                    </div>
                @endforelse
            </div>
        </section>

        <aside class="flex h-full max-h-full flex-col overflow-hidden border-l border-gray-200 bg-white p-6 pb-5 dark:border-gray-700 dark:bg-gray-900 max-[800px]:h-auto max-[800px]:max-h-none max-[800px]:border-l-0 max-[800px]:border-t max-[800px]:p-4">
            <div class="flex shrink-0 items-center justify-between border-b border-gray-200 pb-4 dark:border-gray-700">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ticket actual</p>
                    <h2 class="text-[1.6rem] font-bold tracking-[-0.035em] text-gray-900 dark:text-gray-50">Productos</h2>
                </div>
                @if ($this->cartCount > 0)
                    <button type="button" wire:click="clearCart" class="rounded px-1.5 py-1 text-xs font-bold text-danger-600 transition hover:bg-danger-50 dark:text-danger-400 dark:hover:bg-danger-950/40">Vaciar</button>
                @endif
            </div>

            <div class="flex min-h-0 flex-1 flex-col gap-3.5 overflow-y-auto py-4 pr-1 [scrollbar-color:var(--color-gray-300)_transparent] scrollbar-thin max-[800px]:max-h-70">
                @forelse ($cart as $item)
                    <div class="grid grid-cols-[1fr_auto] items-center gap-x-2.5 gap-y-1 border-b border-dashed border-gray-200 pb-3 last:border-b-0 last:pb-0 dark:border-gray-700" wire:key="cart-{{ $item['inventory_id'] }}">
                        <div class="flex min-w-0 flex-col gap-0.5">
                            <strong class="truncate text-[0.82rem] text-gray-900 dark:text-gray-50">{{ $item['name'] }}</strong>
                            <span class="text-[0.7rem] text-gray-500 dark:text-gray-400">${{ number_format((float) $item['price'], 2) }} c/u</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" wire:click="decrementProduct({{ $item['inventory_id'] }})" aria-label="Disminuir cantidad" class="grid size-6 place-items-center rounded border border-gray-200 bg-gray-50 text-base leading-none text-gray-900 transition hover:border-gray-400 hover:bg-gray-100 focus-visible:outline-3 focus-visible:outline-info-500/35 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-50 dark:hover:bg-gray-700">−</button>
                            <span class="min-w-5 text-center text-xs font-bold text-gray-900 dark:text-gray-50">{{ $item['quantity'] }}</span>
                            <button type="button" wire:click="incrementProduct({{ $item['inventory_id'] }})" wire:loading.attr="disabled" wire:target="incrementProduct" aria-label="Aumentar cantidad" class="grid size-6 place-items-center rounded border border-gray-200 bg-gray-50 text-base leading-none text-gray-900 transition hover:border-gray-400 hover:bg-gray-100 focus-visible:outline-3 focus-visible:outline-info-500/35 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-50 dark:hover:bg-gray-700">+</button>
                        </div>
                        <strong class="text-right text-xs text-gray-900 dark:text-gray-50">${{ number_format((float) $item['price'] * $item['quantity'], 2) }}</strong>
                        <button type="button" wire:click="removeProduct({{ $item['inventory_id'] }})" class="col-start-2 justify-self-end p-0.5 text-gray-400 transition hover:text-danger-600 focus-visible:outline-3 focus-visible:outline-info-500/35 dark:hover:text-danger-400" aria-label="Quitar producto">
                            <x-filament::icon icon="heroicon-m-x-mark" class="size-3.5" />
                        </button>
                    </div>
                @empty
                    <div class="flex h-full min-h-45 flex-col items-center justify-center gap-2 text-center text-gray-500 dark:text-gray-400">
                        <span class="mb-1.5 grid size-12 place-items-center rounded-full border border-gray-200 bg-gray-100 text-gray-400 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-500"><x-filament::icon icon="heroicon-o-shopping-bag" class="size-5.5" /></span>
                        <strong class="text-sm text-gray-900 dark:text-gray-50">El ticket está vacío</strong>
                        <span class="text-xs">Agrega productos desde el catálogo.</span>
                    </div>
                @endforelse
            </div>

            <div class="flex shrink-0 flex-col gap-2.5 border-t border-gray-200 py-4 pb-3 dark:border-gray-700">
                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400"><span>Artículos</span><strong class="text-gray-900 dark:text-gray-50">{{ $this->cartCount }}</strong></div>
                <div class="mt-0.5 flex items-baseline justify-between"><span class="text-sm font-bold text-gray-900 dark:text-gray-50">Total a cobrar</span><strong class="text-[1.85rem] font-extrabold tracking-[-0.045em] text-info-600 dark:text-info-400">${{ number_format($this->cartTotal, 2) }}</strong></div>
            </div>

            <div class="mb-3.5 flex shrink-0 flex-col gap-1.5">
                <label for="payment-method" class="text-[0.7rem] font-bold text-gray-500 dark:text-gray-400">Método de pago</label>
                <select id="payment-method" wire:model="paymentMethod" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5 text-xs text-gray-900 outline-none transition focus:border-info-500 focus:ring-4 focus:ring-info-500/15 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-50">
                    @foreach (\App\Enums\PaymentMethod::cases() as $method)
                        <option value="{{ $method->value }}">{{ $method->getLabel() }}</option>
                    @endforeach
                </select>
            </div>

            <button type="button" wire:click="checkout" wire:loading.attr="disabled" class="flex w-full shrink-0 items-center justify-center gap-2 rounded-lg bg-info-600 px-4 py-3.5 text-sm font-extrabold text-white transition hover:-translate-y-0.5 hover:bg-info-700 focus-visible:outline-3 focus-visible:outline-info-500/35 disabled:cursor-not-allowed disabled:opacity-65">
                <span wire:loading.remove wire:target="checkout">Cobrar ${{ number_format($this->cartTotal, 2) }}</span>
                <span wire:loading wire:target="checkout">Procesando...</span>
                <x-filament::icon icon="heroicon-m-arrow-right" wire:loading.remove wire:target="checkout" class="size-4" />
            </button>
        </aside>
    </div>
</x-filament-panels::page>
