<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-100 shadow-sm" role="banner">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" aria-label="Navegación principal">
        <div class="flex justify-between items-center h-16 lg:h-[68px] gap-3">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 text-xl font-bold text-slate-900 hover:opacity-90 transition-opacity flex-shrink-0 group">
                <span class="w-10 h-10 rounded-xl bg-violet-600 flex items-center justify-center text-white text-lg font-extrabold shadow-md group-hover:shadow-lg group-hover:scale-[1.02] transition-all">C</span>
                <span class="hidden sm:inline">ChiclayoTicket</span>
            </a>

            <div class="hidden lg:flex items-center relative group flex-shrink-0">
                <button type="button" class="px-4 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-all duration-200 flex items-center gap-1.5">
                    Categorías
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="absolute top-full left-0 mt-1 py-2 w-56 bg-white rounded-2xl border border-slate-100 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <p class="px-4 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Por tipo</p>
                    @foreach($categories ?? collect() as $cat)
                        <a href="{{ route('events.index', ['category_slug' => $cat->slug]) }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-violet-600 rounded-xl mx-1.5 transition-colors">{{ $cat->name }}</a>
                    @endforeach
                    @if(empty($categories) || $categories->isEmpty())
                        <a href="{{ route('events.index') }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-violet-600 rounded-xl mx-1.5 transition-colors">Todos los eventos</a>
                        <a href="{{ route('events.index', ['category_slug' => 'conciertos']) }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-violet-600 rounded-xl mx-1.5 transition-colors">Conciertos</a>
                        <a href="{{ route('events.index', ['category_slug' => 'deportes']) }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-violet-600 rounded-xl mx-1.5 transition-colors">Deportes</a>
                        <a href="{{ route('events.index', ['category_slug' => 'teatro']) }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-violet-600 rounded-xl mx-1.5 transition-colors">Teatro</a>
                    @endif
                    <div class="border-t border-slate-100 my-2"></div>
                    <p class="px-4 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Por ciudad</p>
                    <a href="{{ route('events.index', ['city' => 'Lima']) }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-violet-600 rounded-xl mx-1.5 transition-colors">Lima</a>
                    <a href="{{ route('events.index', ['city' => 'Arequipa']) }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-violet-600 rounded-xl mx-1.5 transition-colors">Arequipa</a>
                    <a href="{{ route('events.index', ['city' => 'Cusco']) }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-violet-600 rounded-xl mx-1.5 transition-colors">Cusco</a>
                </div>
            </div>

            {{-- Buscador --}}
            <div class="hidden lg:flex flex-1 justify-center min-w-0 px-4">
                <button type="button" @click="$store.search.open = true" class="relative w-full max-w-xl h-11 pl-11 pr-5 rounded-full border border-slate-200 bg-slate-50/80 hover:bg-white text-left text-slate-500 shadow-sm hover:border-slate-300 hover:shadow-md focus:outline-none focus:border-violet-600 focus:ring-2 focus:ring-violet-500/20 transition-all duration-200 text-sm cursor-pointer flex items-center group">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 group-hover:text-violet-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Buscar eventos, artistas o lugares...</span>
                </button>
            </div>

            <div class="flex items-center gap-1 sm:gap-2 flex-shrink-0">
                <a href="{{ route('cart.index') }}" class="relative p-2.5 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-violet-600 transition-all duration-200 group" aria-label="Resumen de compra">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <template x-if="$store.cart.count > 0">
                        <span x-text="$store.cart.count" class="absolute -top-0.5 -right-0.5 bg-violet-600 text-white text-xs font-bold min-w-[20px] h-5 rounded-full flex items-center justify-center shadow"></span>
                    </template>
                </a>
                @auth
                    <a href="{{ route('cuenta.dashboard') }}" class="hidden sm:inline px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-violet-600 hover:bg-slate-50 rounded-xl transition-all duration-200">Mi cuenta</a>
                    @if(auth()->user()->isOrganizer())
                        <a href="{{ route('organizer.dashboard') }}" class="hidden sm:inline px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-xl hover:text-violet-600 transition-all duration-200">Panel</a>
                    @endif
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="hidden sm:inline px-4 py-2.5 text-sm font-medium text-white bg-slate-800 hover:bg-slate-700 rounded-xl transition-all duration-200">Admin</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">@csrf<button type="submit" class="px-4 py-2.5 text-sm font-medium text-slate-500 hover:text-slate-700 rounded-xl hover:bg-slate-100 transition-colors">Salir</button></form>
                @else
                    <a href="{{ route('register') }}" @click.prevent="$store.auth.open = true; $store.auth.mode = 'register'" class="hidden sm:inline px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-violet-600 rounded-xl cursor-pointer transition-colors">Registrarse</a>
                    <a href="{{ route('login') }}" @click.prevent="$store.auth.open = true; $store.auth.mode = 'login'" class="px-5 py-2.5 text-sm font-bold text-white bg-violet-600 rounded-xl hover:bg-violet-700 shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer">Iniciar sesión</a>
                @endauth
                <button id="menu-btn" type="button" class="lg:hidden p-2.5 rounded-xl text-slate-600 hover:bg-slate-100 transition-colors" aria-label="Menú">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="hidden lg:hidden border-t border-slate-100 py-4">
            <div class="px-2 pb-4">
                <button type="button" @click="$store.search.open = true; document.getElementById('menu-btn') && document.getElementById('mobile-menu').classList.add('hidden')" class="relative w-full h-12 pl-12 pr-4 rounded-xl border border-slate-200 bg-slate-50 text-left text-slate-500 text-sm shadow-sm focus:outline-none focus:border-violet-600 focus:ring-2 focus:ring-violet-500/20">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Buscar eventos o artistas</span>
                </button>
            </div>
            <div class="flex flex-col gap-0.5">
                <a href="{{ route('events.index') }}" class="px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 font-medium text-sm">Eventos</a>
                @auth
                    <a href="{{ route('cuenta.dashboard') }}" class="px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 font-medium text-sm">Mi cuenta</a>
                    @if(auth()->user()->isOrganizer())
                        <a href="{{ route('organizer.dashboard') }}" class="px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 font-medium text-sm">Panel organizador</a>
                    @endif
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 font-medium text-sm">Admin</a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>
</header>
