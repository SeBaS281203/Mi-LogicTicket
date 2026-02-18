<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Entradas para Eventos') | {{ config('app.name') }}</title>
    <meta name="description" content="@yield('meta_description', 'Compra entradas para conciertos, deportes, teatro y más. LogicTicket - Tu marketplace de eventos.')">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    <meta name="theme-color" content="#00a650">
    <link rel="canonical" href="{{ url()->current() }}">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                        colors: {
                            brand: { DEFAULT: '#00a650', hover: '#009345', light: '#e6f7ef' },
                            surface: '#f5f7fa'
                        },
                        borderRadius: { card: '1rem', 'card-lg': '1.25rem' },
                        transitionDuration: { global: '300ms' },
                        animation: { 'fade-in': 'fadeIn 0.4s ease-out', 'slide-up': 'slideUp 0.35s ease-out' },
                        keyframes: {
                            fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                            slideUp: { '0%': { opacity: '0', transform: 'translateY(8px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } }
                        }
                    }
                }
            }
        </script>
    @endif
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        [data-ds] {
            --brand: #00a650;
            --brand-hover: #009345;
            --brand-light: #e6f7ef;
            --surface: #f5f7fa;
            --radius-card: 1rem;
            --radius-card-lg: 1.25rem;
            --duration-global: 300ms;
        }
        .btn-brand { background-color: var(--brand); color: #fff; transition-duration: var(--duration-global); }
        .btn-brand:hover { background-color: var(--brand-hover); }
    </style>
    @stack('styles')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', function() {
            Alpine.store('auth', { open: false, mode: 'login' });
        });
    </script>
</head>
<body class="bg-surface text-neutral-800 antialiased min-h-screen flex flex-col font-sans transition-global" data-ds x-data="{}" @if(session('auth_modal')) data-auth-modal="{{ session('auth_modal') }}" @endif>
    @include('components.navbar')

    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 w-full">
            <div class="bg-brand-light border border-brand/20 text-[#008f47] px-4 py-3 rounded-card text-sm animate-fade-in">{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 w-full">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-card text-sm animate-fade-in">{{ session('error') }}</div>
        </div>
    @endif
    @if(session('info'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 w-full">
            <div class="bg-sky-50 border border-sky-200 text-sky-800 px-4 py-3 rounded-card text-sm animate-fade-in">{{ session('info') }}</div>
        </div>
    @endif

    <main class="flex-1" role="main">
        @yield('content')
    </main>

    @include('components.footer')

    @guest
    @include('components.auth-modal')
    @endguest

    <button type="button" id="scroll-to-top" class="fixed bottom-6 right-6 z-40 w-12 h-12 rounded-full bg-brand text-white shadow-lg hover:bg-brand-hover opacity-0 pointer-events-none duration-global transition-all flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-brand/40 focus:ring-offset-2" aria-label="Volver arriba">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
    </button>

    <script>
        (function() {
            var params = new URLSearchParams(window.location.search);
            var auth = params.get('auth');
            var bodyModal = document.body.getAttribute('data-auth-modal');
            if (auth === 'login' || auth === 'register') {
                window.__authModalOnLoad = auth;
                if (window.history && window.history.replaceState) {
                    params.delete('auth');
                    var newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
                    window.history.replaceState({}, '', newUrl);
                }
            } else if (bodyModal === 'login' || bodyModal === 'register') {
                window.__authModalOnLoad = bodyModal;
            }
        })();
        document.addEventListener('alpine:init', function() {
            if (window.__authModalOnLoad) {
                Alpine.store('auth').open = true;
                Alpine.store('auth').mode = window.__authModalOnLoad;
                window.__authModalOnLoad = null;
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            var mobileMenu = document.getElementById('mobile-menu');
            var menuBtn = document.getElementById('menu-btn');
            if (menuBtn && mobileMenu) {
                menuBtn.addEventListener('click', function() { mobileMenu.classList.toggle('hidden'); });
            }
            var scrollBtn = document.getElementById('scroll-to-top');
            if (scrollBtn) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 400) {
                        scrollBtn.classList.remove('opacity-0', 'pointer-events-none');
                    } else {
                        scrollBtn.classList.add('opacity-0', 'pointer-events-none');
                    }
                });
                scrollBtn.addEventListener('click', function() { window.scrollTo({ top: 0, behavior: 'smooth' }); });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
