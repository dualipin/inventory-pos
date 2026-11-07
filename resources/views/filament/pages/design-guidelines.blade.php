<x-filament-panels::page>
    <div class="space-y-8 pb-12">
        <!-- HEADER OVERVIEW -->
        <header class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xs dark:border-gray-700 dark:bg-gray-900">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-info-500/20 bg-info-50 px-2.5 py-0.5 text-xs font-bold text-info-700 dark:bg-info-950/40 dark:text-info-300">
                        Design System: Inventory POS
                    </span>
                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-50 sm:text-3xl">
                        The High-Throughput Counter Terminal
                    </h1>
                    <p class="mt-1.5 max-w-2xl text-sm text-gray-600 dark:text-gray-400">
                        Catálogo vivo de componentes, tokens y patrones de interacción. Diseñado para garantizar velocidad operativa, legibilidad a 1 metro de distancia y consistencia visual en todas las pantallas.
                    </p>
                </div>
                <div class="flex shrink-0 items-center gap-3">
                    <a
                        href="{{ route('filament.admin.pages.point-of-sale') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-info-600 px-4 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-info-700 focus-visible:outline-3 focus-visible:outline-info-500/35"
                    >
                        <x-filament::icon icon="heroicon-m-calculator" class="size-4" />
                        Ir al Punto de Venta
                    </a>
                </div>
            </div>

            <!-- ANCHOR JUMP LINKS -->
            <nav class="mt-6 flex flex-wrap gap-2 border-t border-gray-100 pt-4 text-xs font-semibold text-gray-600 dark:border-gray-800 dark:text-gray-400">
                <a href="#estado-alertas" class="rounded-lg bg-gray-100 px-3 py-1.5 transition hover:bg-gray-200 hover:text-gray-900 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-gray-100">1. Estado y Alertas</a>
                <a href="#busqueda-atajos" class="rounded-lg bg-gray-100 px-3 py-1.5 transition hover:bg-gray-200 hover:text-gray-900 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-gray-100">2. Búsqueda y Atajos</a>
                <a href="#fichas-producto" class="rounded-lg bg-gray-100 px-3 py-1.5 transition hover:bg-gray-200 hover:text-gray-900 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-gray-100">3. Fichas de Producto</a>
                <a href="#controles-ticket" class="rounded-lg bg-gray-100 px-3 py-1.5 transition hover:bg-gray-200 hover:text-gray-900 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-gray-100">4. Controles de Ticket</a>
                <a href="#calculo-efectivo" class="rounded-lg bg-gray-100 px-3 py-1.5 transition hover:bg-gray-200 hover:text-gray-900 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-gray-100">5. Cobro y Cambio</a>
                <a href="#tokens-tipografia" class="rounded-lg bg-gray-100 px-3 py-1.5 transition hover:bg-gray-200 hover:text-gray-900 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-gray-100">6. Tokens y Color</a>
            </nav>
        </header>

        <!-- SECTION 1: ESTADO Y ALERTAS -->
        <section id="estado-alertas" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xs dark:border-gray-700 dark:bg-gray-900">
            <div class="mb-4 flex items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-800">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-50">1. Indicadores de Estado y Alertas</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Codificación dual de estados: color más texto/ícono para visibilidad inmediata.</p>
                </div>
                <span class="rounded bg-gray-100 px-2 py-0.5 text-[0.7rem] font-bold text-gray-600 dark:bg-gray-800 dark:text-gray-300">Dual-Coded Rule</span>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Conexión -->
                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/40">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Baliza de Conexión</span>
                    <div class="mt-3 flex flex-wrap gap-4">
                        <div class="inline-flex items-center gap-2 text-xs font-bold text-success-600 dark:text-success-400">
                            <span class="size-2 rounded-full bg-success-500 ring-4 ring-success-500/20"></span>
                            En línea
                        </div>
                        <div class="inline-flex items-center gap-2 text-xs font-bold text-danger-600 dark:text-danger-400">
                            <span class="size-2 rounded-full bg-danger-500 ring-4 ring-danger-500/20"></span>
                            Desconectado
                        </div>
                    </div>
                    <p class="mt-3 text-xs text-gray-500">Señal de pulso para verificación inmediata de red en terminales de caja.</p>
                </div>

                <!-- Pastillas de Stock -->
                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/40">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Pastilla de Stock</span>
                        <button
                            type="button"
                            wire:click="toggleDemoStock"
                            class="rounded border border-gray-300 bg-white px-2 py-0.5 text-[0.7rem] font-bold text-gray-700 shadow-2xs hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200"
                        >Alternar (Actual: {{ $demoStock }})</button>
                    </div>

                    <div class="mt-3 flex flex-wrap items-center gap-3">
                        <span class="@if ($demoStock <= 5) text-danger-600 dark:text-danger-400 border-danger-500/30 @else text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700 @endif inline-flex shrink-0 items-center gap-1 rounded-full border bg-white px-2.5 py-0.5 text-[0.6875rem] font-semibold dark:bg-gray-900">
                            <span class="@if ($demoStock <= 5) bg-danger-500 @else bg-success-500 @endif size-1.5 rounded-full"></span>
                            {{ $demoStock }} disp.
                        </span>
                        <span class="text-xs text-gray-500">
                            @if ($demoStock <= 5)
                                <strong class="text-danger-600">Alerta de stock bajo (≤ 5)</strong>
                            @else
                                <strong class="text-success-600">Nivel saludable</strong>
                            @endif
                        </span>
                    </div>
                    <p class="mt-3 text-xs text-gray-500">Cambia automáticamente a rojo carmesí cuando quedan 5 unidades o menos.</p>
                </div>

                <!-- Resumen de Última Venta -->
                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/40">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Notificación de Venta</span>
                    <div class="mt-2 rounded-lg border border-success-500/30 bg-success-50/80 p-3 text-xs dark:border-success-500/30 dark:bg-success-950/40">
                        <div class="flex items-center justify-between font-bold text-success-800 dark:text-success-300">
                            <span class="inline-flex items-center gap-1.5">
                                <x-filament::icon icon="heroicon-m-check-circle" class="size-4" />
                                Folio V-00142
                            </span>
                            <span class="text-[0.7rem] text-success-600">Entregado</span>
                        </div>
                        <div class="mt-1.5 flex justify-between text-gray-600 dark:text-gray-300">
                            <span>Total: $145.50</span>
                            <span class="font-extrabold text-success-700 dark:text-success-400">Cambio: $54.50</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 2: BÚSQUEDA Y ATAJOS -->
        <section id="busqueda-atajos" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xs dark:border-gray-700 dark:bg-gray-900">
            <div class="mb-4 flex items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-800">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-50">2. Ergonomía de Búsqueda y Atajos de Teclado</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Atajos directos para operar el mostrador sin despegar las manos del teclado o escáner.</p>
                </div>
                <span class="rounded bg-gray-100 px-2 py-0.5 text-[0.7rem] font-bold text-gray-600 dark:bg-gray-800 dark:text-gray-300">Keyboard First</span>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div>
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">Barra de Búsqueda de Mostrador</span>
                    <div class="relative mt-2">
                        <x-filament::icon icon="heroicon-o-magnifying-glass" class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 size-5 text-gray-400" />
                        <input
                            type="text"
                            readonly
                            value="Leche Lala Entera"
                            class="w-full rounded-xl border border-gray-200 bg-white py-3.5 pl-12 pr-16 text-sm text-gray-900 shadow-xs outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-gray-50"
                        >
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-1.5">
                            <span class="rounded border border-gray-200 bg-gray-100 px-1.5 py-0.5 text-xs font-semibold text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">/</span>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">
                        Presionar <kbd class="rounded border border-gray-300 bg-gray-100 px-1 py-0.5 text-xs dark:border-gray-600 dark:bg-gray-800">/</kbd> enfoca automáticamente el campo desde cualquier parte de la pantalla. Presionar <kbd class="rounded border border-gray-300 bg-gray-100 px-1 py-0.5 text-xs dark:border-gray-600 dark:bg-gray-800">Enter</kbd> agrega de inmediato la coincidencia exacta de código de barras.
                    </p>
                </div>

                <div class="flex flex-col justify-between rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/40">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Atajos Globales de Caja</span>
                    <ul class="mt-2 space-y-2 text-xs text-gray-700 dark:text-gray-300">
                        <li class="flex items-center justify-between border-b border-gray-200/60 pb-1.5 dark:border-gray-700/60">
                            <span>Enfocar barra de búsqueda / escanear</span>
                            <kbd class="rounded border border-gray-300 bg-white px-2 py-0.5 font-bold dark:border-gray-600 dark:bg-gray-900">/</kbd>
                        </li>
                        <li class="flex items-center justify-between border-b border-gray-200/60 pb-1.5 dark:border-gray-700/60">
                            <span>Agregar coincidencia exacta o escaneada</span>
                            <kbd class="rounded border border-gray-300 bg-white px-2 py-0.5 font-bold dark:border-gray-600 dark:bg-gray-900">Enter</kbd>
                        </li>
                        <li class="flex items-center justify-between border-b border-gray-200/60 pb-1.5 dark:border-gray-700/60">
                            <span>Limpiar búsqueda / cerrar avisos</span>
                            <kbd class="rounded border border-gray-300 bg-white px-2 py-0.5 font-bold dark:border-gray-600 dark:bg-gray-900">Esc</kbd>
                        </li>
                        <li class="flex items-center justify-between">
                            <span>Confirmación destructiva requerida en "Vaciar"</span>
                            <span class="font-semibold text-danger-600">wire:confirm</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- SECTION 3: FICHAS DE PRODUCTO -->
        <section id="fichas-producto" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xs dark:border-gray-700 dark:bg-gray-900">
            <div class="mb-4 flex items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-800">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-50">3. Fichas de Producto Canónicas</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Tarjetas de alta densidad con micro-elevación táctil y área de precio prominente.</p>
                </div>
                <span class="rounded bg-gray-100 px-2 py-0.5 text-[0.7rem] font-bold text-gray-600 dark:bg-gray-800 dark:text-gray-300">Card Anatomy</span>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Preview Tarjeta Normal -->
                <div class="group relative flex gap-3.5 rounded-[0.85rem] border border-gray-200 bg-white p-3.5 text-left shadow-xs transition hover:-translate-y-0.5 hover:border-info-300 hover:shadow-md dark:border-gray-700 dark:bg-gray-900 dark:hover:border-info-600">
                    <span class="grid size-15 shrink-0 place-items-center overflow-hidden rounded-[0.65rem] border border-gray-200 bg-info-50 dark:border-gray-700 dark:bg-info-950/40">
                        <x-filament::icon icon="heroicon-o-cube" class="size-7 text-info-400" />
                    </span>
                    <span class="flex min-w-0 flex-1 flex-col justify-between gap-1.5">
                        <span class="flex items-center justify-between gap-2 leading-none">
                            <span class="truncate text-[0.6875rem] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Lácteos</span>
                            <span class="inline-flex shrink-0 items-center gap-1 rounded-full border border-gray-200 bg-gray-50 px-2 py-0.5 text-[0.6875rem] font-semibold text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                <span class="size-1.5 rounded-full bg-success-500"></span>
                                18 disp.
                            </span>
                        </span>
                        <span class="line-clamp-2 min-h-[2.1rem] text-sm font-semibold leading-snug text-gray-900 dark:text-gray-50">Leche Lala Entera 1 L UHT</span>
                        <span class="mt-auto flex items-center justify-between gap-2 pt-0.5">
                            <strong class="text-[1.05rem] font-extrabold tracking-tight text-info-600 dark:text-info-400">$26.00</strong>
                            <span class="grid size-7 place-items-center rounded-full border border-info-500/25 bg-info-50 text-info-600 transition group-hover:scale-105 group-hover:bg-info-600 group-hover:text-white dark:bg-info-950/40 dark:text-info-300">
                                <x-filament::icon icon="heroicon-m-plus" class="size-3.5" />
                            </span>
                        </span>
                    </span>
                </div>

                <!-- Preview Tarjeta Stock Bajo -->
                <div class="group relative flex gap-3.5 rounded-[0.85rem] border border-danger-300 bg-white p-3.5 text-left shadow-xs transition hover:-translate-y-0.5 hover:shadow-md dark:border-danger-700/60 dark:bg-gray-900">
                    <span class="grid size-15 shrink-0 place-items-center overflow-hidden rounded-[0.65rem] border border-gray-200 bg-info-50 dark:border-gray-700 dark:bg-info-950/40">
                        <x-filament::icon icon="heroicon-o-cube" class="size-7 text-info-400" />
                    </span>
                    <span class="flex min-w-0 flex-1 flex-col justify-between gap-1.5">
                        <span class="flex items-center justify-between gap-2 leading-none">
                            <span class="truncate text-[0.6875rem] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Bebidas</span>
                            <span class="inline-flex shrink-0 items-center gap-1 rounded-full border border-danger-500/30 bg-gray-50 px-2 py-0.5 text-[0.6875rem] font-semibold text-danger-600 dark:bg-gray-800 dark:text-danger-400">
                                <span class="size-1.5 rounded-full bg-danger-500"></span>
                                2 disp.
                            </span>
                        </span>
                        <span class="line-clamp-2 min-h-[2.1rem] text-sm font-semibold leading-snug text-gray-900 dark:text-gray-50">Coca-Cola Original 600 ml</span>
                        <span class="mt-auto flex items-center justify-between gap-2 pt-0.5">
                            <strong class="text-[1.05rem] font-extrabold tracking-tight text-info-600 dark:text-info-400">$18.50</strong>
                            <span class="grid size-7 place-items-center rounded-full border border-info-500/25 bg-info-50 text-info-600 transition group-hover:scale-105 group-hover:bg-info-600 group-hover:text-white dark:bg-info-950/40 dark:text-info-300">
                                <x-filament::icon icon="heroicon-m-plus" class="size-3.5" />
                            </span>
                        </span>
                    </span>
                </div>

                <!-- Anatomía y Reglas -->
                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 text-xs text-gray-600 dark:border-gray-800 dark:bg-gray-800/40 dark:text-gray-400">
                    <span class="font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">Reglas de Ficha</span>
                    <ul class="mt-2 space-y-1.5 list-disc pl-4">
                        <li><strong>Radio exterior:</strong> 14px (`rounded-[0.85rem]`).</li>
                        <li><strong>Contenedor miniatura:</strong> 60x60px (`size-15`) con borde suave.</li>
                        <li><strong>Título:</strong> Truncado estricto a 2 líneas (`line-clamp-2 min-h-[2.1rem]`).</li>
                        <li><strong>Precio:</strong> Siempre en negrita (`font-extrabold`) y color señal (`text-info-600`).</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- SECTION 4: CONTROLES DE TICKET -->
        <section id="controles-ticket" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xs dark:border-gray-700 dark:bg-gray-900">
            <div class="mb-4 flex items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-800">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-50">4. Controles de Ticket y Cantidad</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Entrada numérica directa con teclado y botones paso a paso.</p>
                </div>
                <span class="rounded bg-gray-100 px-2 py-0.5 text-[0.7rem] font-bold text-gray-600 dark:bg-gray-800 dark:text-gray-300">Stepper Controls</span>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Sandbox Interactivo de Renglón -->
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/50">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Demo Interactivo en Vivo</span>
                    
                    <div class="mt-3 grid grid-cols-[1fr_auto] items-center gap-x-3 gap-y-1 rounded-lg border border-gray-200 bg-white p-3.5 shadow-2xs dark:border-gray-700 dark:bg-gray-900">
                        <div class="flex min-w-0 flex-col gap-0.5">
                            <strong class="truncate text-xs font-semibold text-gray-900 dark:text-gray-50">Queso Panela Nochebuena 400g</strong>
                            <span class="text-[0.6875rem] text-gray-500 dark:text-gray-400">$68.00 c/u</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <button
                                type="button"
                                wire:click="decrementDemoQuantity"
                                class="grid size-6 place-items-center rounded border border-gray-200 bg-gray-50 text-base leading-none text-gray-900 transition hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-50"
                            >−</button>
                            <input
                                type="number"
                                min="1"
                                max="10"
                                readonly
                                value="{{ $demoQuantity }}"
                                class="w-11 rounded border border-gray-200 bg-white py-0.5 text-center text-xs font-bold text-gray-900 shadow-2xs dark:border-gray-700 dark:bg-gray-900 dark:text-gray-50"
                            >
                            <button
                                type="button"
                                wire:click="incrementDemoQuantity"
                                class="grid size-6 place-items-center rounded border border-gray-200 bg-gray-50 text-base leading-none text-gray-900 transition hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-50"
                            >+</button>
                        </div>
                        <strong class="text-right text-xs text-gray-900 dark:text-gray-50">
                            ${{ number_format(68.00 * $demoQuantity, 2) }}
                        </strong>
                        <button type="button" class="col-start-2 justify-self-end p-0.5 text-gray-400 hover:text-danger-600">
                            <x-filament::icon icon="heroicon-m-x-mark" class="size-3.5" />
                        </button>
                    </div>

                    <p class="mt-3 text-xs text-gray-500">
                        El cajero puede hacer clic en <kbd>+</kbd>/<kbd>−</kbd> o escribir directamente el número con el teclado numérico si el cliente lleva 10 piezas.
                    </p>
                </div>

                <!-- Buenas Prácticas de Seguridad -->
                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 text-xs text-gray-600 dark:border-gray-800 dark:bg-gray-800/40 dark:text-gray-400">
                    <span class="font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">Garantías de Seguridad</span>
                    <ul class="mt-2.5 space-y-2 list-disc pl-4">
                        <li><strong>Confirmación Obligatoria:</strong> El botón "Vaciar" debe portar siempre <code>wire:confirm</code> para evitar vaciar órdenes de 20 artículos por un clic accidental.</li>
                        <li><strong>Límite de Stock:</strong> La entrada numérica no puede superar el inventario físico disponible en el momento de la venta.</li>
                        <li><strong>Alcance de Carga:</strong> Todo botón interactivo debe acotar su carga con <code>wire:target="accion(id)"</code> para no deshabilitar otros elementos de la pantalla.</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- SECTION 5: COBRO EN EFECTIVO Y CAMBIO -->
        <section id="calculo-efectivo" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xs dark:border-gray-700 dark:bg-gray-900">
            <div class="mb-4 flex items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-800">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-50">5. Cobro en Efectivo y Cálculo de Cambio en Vivo</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Módulo de cobro rápido con denominaciones de billetes y cálculo instantáneo de cambio.</p>
                </div>
                <span class="rounded bg-gray-100 px-2 py-0.5 text-[0.7rem] font-bold text-gray-600 dark:bg-gray-800 dark:text-gray-300">Live Cash Tender</span>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Sandbox de Billetes -->
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/50">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-700 dark:text-gray-300">
                            <span class="size-2 rounded-full bg-success-500"></span>
                            Simulador de Pago
                        </span>
                        <button
                            type="button"
                            wire:click="setExactDemoTender"
                            class="rounded px-2 py-0.5 text-[0.7rem] font-bold text-info-600 hover:bg-info-50 dark:text-info-400"
                        >Exacto (${{ number_format($demoTotal, 2) }})</button>
                    </div>

                    <!-- Billetes -->
                    <div class="mt-3 grid grid-cols-4 gap-2">
                        @foreach ([50, 100, 200, 500] as $bill)
                            <button
                                type="button"
                                wire:click="setDemoTender({{ $bill }})"
                                class="rounded-lg border border-gray-200 bg-white py-2 text-xs font-bold text-gray-800 shadow-2xs transition hover:border-info-400 hover:bg-info-50 active:scale-95 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                            >${{ $bill }}</button>
                        @endforeach
                    </div>

                    <!-- Input Recibido -->
                    <div class="relative mt-3">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">$</span>
                        <input
                            type="number"
                            step="any"
                            wire:model.live.debounce.150ms="demoTender"
                            placeholder="Monto recibido"
                            class="w-full rounded-lg border border-gray-200 bg-white py-2.5 pl-7 pr-3 text-xs font-bold text-gray-900 shadow-2xs outline-none focus:border-info-500 focus:ring-3 focus:ring-info-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-50"
                        >
                    </div>

                    <!-- Indicador de Cambio -->
                    <div class="mt-3 rounded-lg border p-3 text-xs @if ((float) $demoTender >= $demoTotal) border-success-200 bg-success-50/60 dark:border-success-800 dark:bg-success-950/40 @else border-danger-200 bg-danger-50/60 dark:border-danger-800 dark:bg-danger-950/40 @endif">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-gray-600 dark:text-gray-400">Total a cobrar:</span>
                            <strong class="text-sm font-bold text-gray-900 dark:text-gray-50">${{ number_format($demoTotal, 2) }}</strong>
                        </div>
                        <div class="mt-1 flex items-center justify-between border-t border-gray-200/50 pt-1.5 dark:border-gray-700/50">
                            @if ((float) $demoTender >= $demoTotal)
                                <span class="font-bold text-success-800 dark:text-success-300">Cambio a entregar:</span>
                                <strong class="text-base font-extrabold text-success-700 dark:text-success-400">
                                    ${{ number_format($this->demoChangeDue, 2) }}
                                </strong>
                            @else
                                <span class="font-bold text-danger-700 dark:text-danger-400">Monto insuficiente (Faltan):</span>
                                <strong class="text-xs font-bold text-danger-600 dark:text-danger-400">
                                    ${{ number_format($this->demoShortage, 2) }}
                                </strong>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex flex-col justify-between rounded-xl border border-gray-100 bg-gray-50 p-4 text-xs text-gray-600 dark:border-gray-800 dark:bg-gray-800/40 dark:text-gray-400">
                    <div>
                        <span class="font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">Ergonomía de Arqueo y Despacho</span>
                        <p class="mt-2">
                            Al operar en efectivo exclusivo, calcular el cambio es la responsabilidad crítica del punto de venta. Los botones de billetes directos ($50, $100, $200, $500) eliminan por completo el uso de calculadoras externas y aceleran la cola de clientes en horas pico.
                        </p>
                    </div>
                    <div class="mt-4 rounded-lg border border-info-500/20 bg-info-50/70 p-3 text-info-800 dark:bg-info-950/40 dark:text-info-300">
                        <strong>Botón de Cobro:</strong> Se desactiva automáticamente si el importe recibido es menor al total a cobrar o si el ticket está vacío.
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 6: TOKENS Y TIPOGRAFÍA -->
        <section id="tokens-tipografia" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xs dark:border-gray-700 dark:bg-gray-900">
            <div class="mb-4 flex items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-800">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-50">6. Tokens de Color y Tipografía Normativa</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Paleta oficial y escala de Instrument Sans para pantallas de inventario y caja.</p>
                </div>
                <span class="rounded bg-gray-100 px-2 py-0.5 text-[0.7rem] font-bold text-gray-600 dark:bg-gray-800 dark:text-gray-300">DESIGN.md Ramp</span>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Muestras de Color -->
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Paleta Oficial</span>
                    <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-3">
                        <div class="rounded-xl border border-gray-200 bg-white p-3 shadow-2xs dark:border-gray-700 dark:bg-gray-900">
                            <div class="h-10 rounded-lg bg-info-600"></div>
                            <span class="mt-2 block text-xs font-bold text-gray-900 dark:text-gray-50">Signal Sky (info-600)</span>
                            <span class="text-[0.6875rem] text-gray-500">#0284c7 · Acción / Precio</span>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3 shadow-2xs dark:border-gray-700 dark:bg-gray-900">
                            <div class="h-10 rounded-lg bg-success-500"></div>
                            <span class="mt-2 block text-xs font-bold text-gray-900 dark:text-gray-50">Emerald (success-500)</span>
                            <span class="text-[0.6875rem] text-gray-500">#10b981 · En línea / Stock OK</span>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3 shadow-2xs dark:border-gray-700 dark:bg-gray-900">
                            <div class="h-10 rounded-lg bg-danger-500"></div>
                            <span class="mt-2 block text-xs font-bold text-gray-900 dark:text-gray-50">Crimson (danger-500)</span>
                            <span class="text-[0.6875rem] text-gray-500">#ef4444 · Stock bajo (≤ 5)</span>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3 shadow-2xs dark:border-gray-700 dark:bg-gray-900">
                            <div class="h-10 rounded-lg bg-gray-50 border border-gray-200"></div>
                            <span class="mt-2 block text-xs font-bold text-gray-900 dark:text-gray-50">Canvas (gray-50)</span>
                            <span class="text-[0.6875rem] text-gray-500">#f9fafb · Fondo principal</span>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3 shadow-2xs dark:border-gray-700 dark:bg-gray-900">
                            <div class="h-10 rounded-lg bg-gray-900"></div>
                            <span class="mt-2 block text-xs font-bold text-gray-900 dark:text-gray-50">Ink (gray-900)</span>
                            <span class="text-[0.6875rem] text-gray-500">#111827 · Tipografía / Oscuro</span>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3 shadow-2xs dark:border-gray-700 dark:bg-gray-900">
                            <div class="h-10 rounded-lg bg-white border border-gray-200"></div>
                            <span class="mt-2 block text-xs font-bold text-gray-900 dark:text-gray-50">Surface (white)</span>
                            <span class="text-[0.6875rem] text-gray-500">#ffffff · Fichas y tarjetas</span>
                        </div>
                    </div>
                </div>

                <!-- Escala Tipográfica -->
                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/40">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Escala Instrument Sans</span>
                    <div class="mt-3 space-y-2.5 text-gray-900 dark:text-gray-50">
                        <div class="flex items-baseline justify-between border-b border-gray-200/60 pb-1.5 dark:border-gray-700/60">
                            <span class="text-xs text-gray-500">Display (Total de Caja)</span>
                            <span class="text-[1.85rem] font-extrabold tracking-[-0.045em] text-info-600">$1,450.00</span>
                        </div>
                        <div class="flex items-baseline justify-between border-b border-gray-200/60 pb-1.5 dark:border-gray-700/60">
                            <span class="text-xs text-gray-500">Headline (Encabezado)</span>
                            <span class="text-[1.6rem] font-bold tracking-[-0.035em]">Nueva venta</span>
                        </div>
                        <div class="flex items-baseline justify-between border-b border-gray-200/60 pb-1.5 dark:border-gray-700/60">
                            <span class="text-xs text-gray-500">Title (Precio de Tarjeta)</span>
                            <span class="text-[1.05rem] font-extrabold text-info-600">$26.00</span>
                        </div>
                        <div class="flex items-baseline justify-between border-b border-gray-200/60 pb-1.5 dark:border-gray-700/60">
                            <span class="text-xs text-gray-500">Body (Título de Producto)</span>
                            <span class="text-sm font-semibold">Leche Lala Entera 1 L</span>
                        </div>
                        <div class="flex items-baseline justify-between">
                            <span class="text-xs text-gray-500">Label (Categoría / Pastilla)</span>
                            <span class="text-[0.6875rem] font-bold uppercase tracking-wider text-gray-500">LÁCTEOS · 18 DISP.</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-filament-panels::page>
