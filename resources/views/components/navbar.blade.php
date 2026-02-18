<header class="sticky top-0 z-50 bg-white border-b border-neutral-100 shadow-sm" role="banner">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" aria-label="Navegación principal">
        <div class="flex justify-between items-center h-16 lg:h-[72px] gap-3">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 text-xl font-bold text-neutral-900 hover:opacity-90 transition-opacity flex-shrink-0">
                <span class="w-10 h-10 rounded-xl bg-[#00a650] flex items-center justify-center text-white text-lg font-extrabold shadow-sm">L</span>
                <span class="hidden sm:inline">LogicTicket</span>
            </a>

            <div class="hidden lg:flex items-center relative group flex-shrink-0">
                <button type="button" class="px-4 py-2.5 rounded-xl text-sm font-medium text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900 transition-colors flex items-center gap-1.5">
                    Descubrir
                    <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="absolute top-full left-0 mt-1 py-2 w-52 bg-white rounded-2xl border border-neutral-100 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                    <a href="{{ route('events.index') }}" class="block px-4 py-2.5 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-[#00a650] rounded-xl mx-1.5 transition-colors">Todos los eventos</a>
                    <a href="{{ route('events.index', ['category_slug' => 'conciertos']) }}" class="block px-4 py-2.5 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-[#00a650] rounded-xl mx-1.5 transition-colors">Conciertos</a>
                    <a href="{{ route('events.index', ['category_slug' => 'deportes']) }}" class="block px-4 py-2.5 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-[#00a650] rounded-xl mx-1.5 transition-colors">Deportes</a>
                    <a href="{{ route('events.index', ['category_slug' => 'teatro']) }}" class="block px-4 py-2.5 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-[#00a650] rounded-xl mx-1.5 transition-colors">Teatro</a>
                    <a href="{{ route('events.index', ['category_slug' => 'conferencias']) }}" class="block px-4 py-2.5 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-[#00a650] rounded-xl mx-1.5 transition-colors">Conferencias</a>
                    <a href="{{ route('events.index', ['category_slug' => 'fiestas']) }}" class="block px-4 py-2.5 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-[#00a650] rounded-xl mx-1.5 transition-colors">Fiestas</a>
                    <a href="{{ route('events.index', ['category_slug' => 'gastronomia']) }}" class="block px-4 py-2.5 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-[#00a650] rounded-xl mx-1.5 transition-colors">Gastronomía</a>
                    <div class="border-t border-neutral-100 my-2"></div>
                    <p class="px-4 py-1.5 text-[10px] font-semibold text-neutral-400 uppercase tracking-wider">Por ciudad</p>
                    <a href="{{ route('events.index', ['city' => 'Lima']) }}" class="block px-4 py-2.5 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-[#00a650] rounded-xl mx-1.5 transition-colors">Lima</a>
                    <a href="{{ route('events.index', ['city' => 'Arequipa']) }}" class="block px-4 py-2.5 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-[#00a650] rounded-xl mx-1.5 transition-colors">Arequipa</a>
                    <a href="{{ route('events.index', ['city' => 'Cusco']) }}" class="block px-4 py-2.5 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-[#00a650] rounded-xl mx-1.5 transition-colors">Cusco</a>
                </div>
            </div>

            {{-- Buscador centrado: max 600px, h-12, pill, lupa izquierda, sombra leve, focus verde --}}
            <div class="hidden lg:flex flex-1 justify-center min-w-0 px-4">
                <form method="GET" action="{{ route('events.index') }}" class="w-full max-w-[600px]">
                    <label for="nav-search" class="sr-only">Buscar eventos</label>
                    <div class="relative w-full">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-neutral-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="q" id="nav-search" value="{{ request('q') }}" placeholder="Buscar por eventos o artistas"
                            class="w-full h-12 pl-12 pr-5 rounded-full border border-neutral-200 bg-white text-neutral-900 placeholder-neutral-400 shadow-sm focus:outline-none focus:border-[#00a650] focus:ring-2 focus:ring-[#00a650]/25 transition-all text-sm">
                    </div>
                </form>
            </div>

            <div class="flex items-center gap-1 sm:gap-2 flex-shrink-0">
                <a href="{{ route('cart.index') }}" class="relative p-2.5 rounded-xl text-neutral-500 hover:bg-neutral-50 hover:text-[#00a650] transition-colors" aria-label="Carrito">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    @php $cartTotal = array_sum(session('cart', [])); @endphp
                    @if($cartTotal > 0)
                        <span class="absolute top-1 right-1 bg-[#00a650] text-white text-xs font-bold min-w-[18px] h-[18px] rounded-full flex items-center justify-center">{{ $cartTotal }}</span>
                    @endif
                </a>
                @auth
                    <a href="{{ route('orders.index') }}" class="hidden sm:inline px-4 py-2.5 text-sm font-medium text-neutral-600 hover:text-[#00a650] transition-colors rounded-xl hover:bg-neutral-50">Mis órdenes</a>
                    <a href="{{ route('login') }}" class="hidden sm:inline px-4 py-2.5 text-sm font-medium text-neutral-600 hover:bg-neutral-50 rounded-xl transition-colors">Mi cuenta</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">@csrf<button type="submit" class="px-4 py-2.5 text-sm font-medium text-neutral-500 hover:text-neutral-700 rounded-xl">Salir</button></form>
                @else
                    <a href="{{ route('register') }}" @click.prevent="$store.auth.open = true; $store.auth.mode = 'register'" class="hidden sm:inline px-4 py-2.5 text-sm font-medium text-neutral-600 hover:text-[#00a650] transition-colors rounded-xl cursor-pointer">Registrarse</a>
                    <a href="{{ route('login') }}" @click.prevent="$store.auth.open = true; $store.auth.mode = 'login'" class="px-5 py-3 text-sm font-bold text-white bg-[#00a650] rounded-2xl hover:bg-[#009345] shadow-sm transition-all cursor-pointer">Iniciar sesión</a>
                @endauth
                <button id="menu-btn" type="button" class="lg:hidden p-2.5 rounded-xl text-neutral-500 hover:bg-neutral-50" aria-label="Menú">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="hidden lg:hidden border-t border-neutral-100 py-4">
            <form method="GET" action="{{ route('events.index') }}" class="px-2 pb-4">
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-neutral-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por eventos o artistas" class="w-full h-12 pl-12 pr-4 rounded-full border border-neutral-200 bg-white text-sm shadow-sm focus:outline-none focus:border-[#00a650] focus:ring-2 focus:ring-[#00a650]/25">
                </div>
            </form>
            <div class="flex flex-col gap-0.5">
                <a href="{{ route('events.index') }}" class="px-4 py-3 rounded-xl text-neutral-600 hover:bg-neutral-50 font-medium text-sm">Eventos</a>
                @auth
                    @if(auth()->user()->isOrganizer())
                        <a href="{{ route('organizer.dashboard') }}" class="px-4 py-3 rounded-xl text-neutral-600 hover:bg-neutral-50 font-medium text-sm">Panel</a>
                    @endif
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-3 rounded-xl text-neutral-600 hover:bg-neutral-50 font-medium text-sm">Admin</a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>
</header>
