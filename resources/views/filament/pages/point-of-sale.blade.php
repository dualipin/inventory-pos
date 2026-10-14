<x-filament-panels::page class="pos-page" :full-height="true">
    <div class="pos-shell">
        <section class="pos-catalog">
            <div class="pos-header">
                <div>
                    <p class="pos-kicker">Caja abierta</p>
                    <h1 class="pos-heading">Nueva venta</h1>
                </div>
                <div class="pos-counter">
                    <span class="pos-counter-dot"></span>
                    En línea
                </div>
            </div>

            <div class="pos-search-wrap">
                <x-filament::icon icon="heroicon-o-magnifying-glass" class="pos-search-icon" />
                <input
                    type="search"
                    wire:model.live.debounce.250ms="search"
                    placeholder="Busca por nombre o escanea un código..."
                    class="pos-search"
                    autofocus
                >
                <kbd class="pos-key">/</kbd>
            </div>

            <div class="pos-catalog-meta">
                <span>{{ $this->products->count() }} productos disponibles</span>
                <span class="pos-hint">Selecciona un producto para agregarlo</span>
            </div>

            <div class="pos-products">
                @forelse ($this->products as $inventory)
                    <button
                        type="button"
                        wire:click="addProduct({{ $inventory->id }})"
                        class="pos-product"
                        title="{{ $inventory->product->name }}"
                    >
                        <span class="pos-product-image">
                            @if ($inventory->product->image_url)
                                <img src="{{ $inventory->product->image_url }}" alt="{{ $inventory->product->name }}" loading="lazy" />
                            @else
                                <x-filament::icon icon="heroicon-o-cube" class="pos-product-placeholder" />
                            @endif
                        </span>

                        <span class="pos-product-copy">
                            <span class="pos-product-header">
                                <span class="pos-product-category">{{ $inventory->product->category?->name ?? 'Producto' }}</span>
                                <span class="pos-product-stock @if ($inventory->stock <= 5) is-low @endif">
                                    <span class="pos-stock-dot"></span>
                                    {{ $inventory->stock }} disp.
                                </span>
                            </span>

                            <span class="pos-product-name">{{ $inventory->product->name }}</span>

                            <span class="pos-product-footer">
                                <strong class="pos-product-price">${{ number_format((float) $inventory->product->price, 2) }}</strong>
                                <span class="pos-add-btn" aria-hidden="true">
                                    <x-filament::icon icon="heroicon-m-plus" />
                                </span>
                            </span>
                        </span>
                    </button>
                @empty
                    <div class="pos-empty">
                        <x-filament::icon icon="heroicon-o-magnifying-glass" />
                        <strong>No encontramos productos</strong>
                        <span>Prueba con otro nombre o código de barras.</span>
                    </div>
                @endforelse
            </div>
        </section>

        <aside class="pos-ticket">
            <div class="pos-ticket-top">
                <div>
                    <p class="pos-kicker">Ticket actual</p>
                    <h2 class="pos-ticket-title">Productos</h2>
                </div>
                @if ($this->cartCount > 0)
                    <button type="button" wire:click="clearCart" class="pos-clear">Vaciar</button>
                @endif
            </div>

            <div class="pos-cart">
                @forelse ($cart as $item)
                    <div class="pos-cart-item" wire:key="cart-{{ $item['inventory_id'] }}">
                        <div class="pos-cart-item-copy">
                            <strong>{{ $item['name'] }}</strong>
                            <span>${{ number_format((float) $item['price'], 2) }} c/u</span>
                        </div>
                        <div class="pos-cart-actions">
                            <button type="button" wire:click="decrementProduct({{ $item['inventory_id'] }})" aria-label="Disminuir cantidad">−</button>
                            <span>{{ $item['quantity'] }}</span>
                            <button type="button" wire:click="incrementProduct({{ $item['inventory_id'] }})" aria-label="Aumentar cantidad">+</button>
                        </div>
                        <strong class="pos-cart-subtotal">${{ number_format((float) $item['price'] * $item['quantity'], 2) }}</strong>
                        <button type="button" wire:click="removeProduct({{ $item['inventory_id'] }})" class="pos-remove" aria-label="Quitar producto">
                            <x-filament::icon icon="heroicon-m-x-mark" />
                        </button>
                    </div>
                @empty
                    <div class="pos-cart-empty">
                        <span class="pos-cart-empty-icon"><x-filament::icon icon="heroicon-o-shopping-bag" /></span>
                        <strong>El ticket está vacío</strong>
                        <span>Agrega productos desde el catálogo.</span>
                    </div>
                @endforelse
            </div>

            <div class="pos-summary">
                <div><span>Artículos</span><strong>{{ $this->cartCount }}</strong></div>
                <div class="pos-total"><span>Total a cobrar</span><strong>${{ number_format($this->cartTotal, 2) }}</strong></div>
            </div>

            <div class="pos-payment">
                <label for="payment-method">Método de pago</label>
                <select id="payment-method" wire:model="paymentMethod">
                    @foreach (\App\Enums\PaymentMethod::cases() as $method)
                        <option value="{{ $method->value }}">{{ $method->getLabel() }}</option>
                    @endforeach
                </select>
            </div>

            <button type="button" wire:click="checkout" wire:loading.attr="disabled" class="pos-charge">
                <span wire:loading.remove wire:target="checkout">Cobrar ${{ number_format($this->cartTotal, 2) }}</span>
                <span wire:loading wire:target="checkout">Procesando...</span>
                <x-filament::icon icon="heroicon-m-arrow-right" wire:loading.remove wire:target="checkout" />
            </button>
        </aside>
    </div>
</x-filament-panels::page>
