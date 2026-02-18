<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') | {{ config('app.name') }} Admin</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700" rel="stylesheet" />
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-900 antialiased min-h-screen font-sans">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-slate-800 text-white flex-shrink-0 flex flex-col">
            <div class="p-4 border-b border-slate-700">
                <a href="{{ route('admin.dashboard') }}" class="text-lg font-bold">LogicTicket Admin</a>
            </div>
            <nav class="p-2 flex-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600' : 'hover:bg-slate-700' }}">Dashboard</a>
                <a href="{{ route('admin.events.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.events.*') ? 'bg-indigo-600' : 'hover:bg-slate-700' }}">Eventos</a>
                <a href="{{ route('admin.events.index', ['status' => 'pending_approval']) }}" class="block px-3 py-2 rounded-lg hover:bg-slate-700">Aprobar eventos</a>
                <a href="{{ route('admin.users.index', ['role' => 'organizer']) }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600' : 'hover:bg-slate-700' }}">Organizadores</a>
                <a href="{{ route('admin.categories.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-600' : 'hover:bg-slate-700' }}">Categorías</a>
                <a href="{{ route('admin.cities.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.cities.*') ? 'bg-indigo-600' : 'hover:bg-slate-700' }}">Ciudades</a>
                <a href="{{ route('admin.banners.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.banners.*') ? 'bg-indigo-600' : 'hover:bg-slate-700' }}">Banners</a>
                <a href="{{ route('admin.settings.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-600' : 'hover:bg-slate-700' }}">Configuración</a>
                <a href="{{ route('admin.reports.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-600' : 'hover:bg-slate-700' }}">Reportes</a>
                <a href="{{ route('admin.libro-reclamaciones.dashboard') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.libro-reclamaciones.*') ? 'bg-indigo-600' : 'hover:bg-slate-700' }}">Libro de Reclamaciones</a>
                <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-700">Usuarios</a>
            </nav>
            <div class="p-2 border-t border-slate-700">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-700 text-sm">Ver sitio</a>
                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-700 text-sm">Cerrar sesión</button>
                </form>
            </div>
        </aside>
        <main class="flex-1 overflow-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                @if(session('success'))
                    <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">{{ session('error') }}</div>
                @endif
                @if(session('info'))
                    <div class="mb-4 bg-sky-50 border border-sky-200 text-sky-800 px-4 py-3 rounded-xl text-sm">{{ session('info') }}</div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
</body>
</html>
