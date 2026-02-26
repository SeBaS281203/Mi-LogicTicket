<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mi cuenta') | {{ config('app.name') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    @stack('styles')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .panel-scroll::-webkit-scrollbar { width: 8px; }
        .panel-scroll::-webkit-scrollbar-track { background: transparent; }
        .panel-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }
        .panel-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 antialiased min-h-screen font-sans" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen lg:flex">
        <div
            x-cloak
            x-show="sidebarOpen"
            x-transition.opacity
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 lg:hidden"
        ></div>

        <aside
            class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200 shadow-xl lg:shadow-none transform transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-auto lg:z-auto lg:flex lg:flex-col"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-bold text-slate-900">
                    <span class="w-9 h-9 rounded-xl bg-violet-600 flex items-center justify-center text-white font-extrabold text-sm">C</span>
                    ChiclayoTicket
                </a>
                <button type="button" class="lg:hidden w-9 h-9 rounded-xl text-slate-500 hover:bg-slate-100" @click="sidebarOpen = false" aria-label="Cerrar menú">
                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="px-4 py-3 border-b border-slate-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-sm font-semibold text-slate-700">
                    {{ mb_substr(auth()->user()->name ?? 'U', 0, 1) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-violet-600 font-medium">Cuenta ID: {{ auth()->id() }}</p>
                </div>
            </div>

            <div class="p-3 border-b border-slate-100" data-panel-menu>
                <label for="cuenta-menu-search" class="sr-only">Buscar módulo</label>
                <div class="relative">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10.8 18a7.2 7.2 0 100-14.4 7.2 7.2 0 000 14.4z"/></svg>
                    <input id="cuenta-menu-search" data-menu-search type="search" placeholder="Buscar opción (acepta tildes)" class="w-full h-10 pl-9 pr-3 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500">
                </div>
            </div>

            <nav class="p-2 flex-1 overflow-y-auto panel-scroll">
                <div data-menu-group class="space-y-1">
                    <p data-menu-title class="px-3 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Mi cuenta</p>
                    <a data-menu-item data-menu-label="Mis entradas dashboard" href="{{ route('cuenta.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('cuenta.dashboard') ? 'bg-violet-50 text-violet-700 font-medium shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">Mis entradas</a>
                    <a data-menu-item data-menu-label="Mis tickets" href="{{ route('cuenta.tickets.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('cuenta.tickets.*') ? 'bg-violet-50 text-violet-700 font-medium shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">Mis tickets</a>
                    <a data-menu-item data-menu-label="Historial de compras órdenes" href="{{ route('orders.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('orders.index') ? 'bg-violet-50 text-violet-700 font-medium shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">Historial de compras</a>
                    <a data-menu-item data-menu-label="Mi perfil configuración personal" href="{{ route('cuenta.perfil.edit') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('cuenta.perfil.*') ? 'bg-violet-50 text-violet-700 font-medium shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">Mi perfil</a>
                </div>

                @if(auth()->user()->isOrganizer())
                    <div data-menu-group class="space-y-1 mt-3">
                        <p data-menu-title class="px-3 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Organizador</p>
                        <a data-menu-item data-menu-label="Panel de eventos organizador" href="{{ route('organizer.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all">Panel de eventos</a>
                    </div>
                @endif

                @if(auth()->user()->isAdmin())
                    <div data-menu-group class="space-y-1 mt-3">
                        <p data-menu-title class="px-3 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Administración</p>
                        <a data-menu-item data-menu-label="Panel admin administración" href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all">Panel admin</a>
                    </div>
                @endif
            </nav>

            <div class="p-2 border-t border-slate-100">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-xl hover:bg-slate-100 text-sm text-slate-600">Ver eventos</a>
                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-xl hover:bg-slate-100 text-sm text-slate-600">Cerrar sesión</button>
                </form>
            </div>
        </aside>

        <div class="flex-1 min-w-0">
            <header class="sticky top-0 z-30 bg-white/90 backdrop-blur-md border-b border-slate-200">
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <button type="button" class="lg:hidden w-10 h-10 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50" @click="sidebarOpen = true" aria-label="Abrir menú">
                            <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <p class="text-sm text-slate-500 truncate">Mi panel de usuario</p>
                    </div>
                    <span class="hidden sm:inline-flex items-center rounded-full bg-violet-50 text-violet-700 px-3 py-1 text-xs font-semibold">Usuario</span>
                </div>
            </header>

            <main class="overflow-auto">
                @include('components.toast-container')
                @include('components.loader-overlay')
                @include('components.confirm-modal')
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        (function () {
            function normalizeText(value) {
                return (value || '')
                    .toString()
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .trim();
            }

            function setupMenuSearch(root) {
                const input = root.querySelector('[data-menu-search]');
                const groups = Array.from(root.parentElement.querySelectorAll('[data-menu-group]'));
                if (!input || !groups.length) return;

                const applyFilter = () => {
                    const query = normalizeText(input.value);

                    groups.forEach((group) => {
                        const title = group.querySelector('[data-menu-title]');
                        const items = Array.from(group.querySelectorAll('[data-menu-item]'));
                        let visibleCount = 0;

                        items.forEach((item) => {
                            const label = normalizeText(item.dataset.menuLabel || item.textContent);
                            const match = !query || label.includes(query);
                            item.classList.toggle('hidden', !match);
                            if (match) visibleCount++;
                        });

                        group.classList.toggle('hidden', visibleCount === 0);
                        if (title) title.classList.toggle('hidden', visibleCount === 0);
                    });
                };

                input.addEventListener('input', applyFilter);
                applyFilter();
            }

            document.querySelectorAll('[data-panel-menu]').forEach(setupMenuSearch);
        })();

        document.addEventListener('alpine:init', function () {
            @if(session('success'))
                if (Alpine.store('toast')) Alpine.store('toast').success(@json(session('success')));
            @endif
            @if(session('error'))
                if (Alpine.store('toast')) Alpine.store('toast').error(@json(session('error')));
            @endif
            @if(session('info'))
                if (Alpine.store('toast')) Alpine.store('toast').info(@json(session('info')));
            @endif
        });
    </script>
    @stack('scripts')
</body>
</html>
