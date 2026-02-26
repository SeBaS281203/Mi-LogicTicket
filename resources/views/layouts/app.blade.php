<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Entradas para Eventos') | {{ config('app.name') }}</title>
    <meta name="description" content="@yield('meta_description', 'Compra entradas para conciertos, deportes, teatro y más. ChiclayoTicket - Tu marketplace de eventos.')">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    <meta name="theme-color" content="#7c3aed">
    <link rel="canonical" href="{{ url()->current() }}">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                        colors: {
                            brand: { DEFAULT: '#7c3aed', hover: '#6d28d9', light: '#ede9fe', dark: '#5b21b6' },
                            surface: '#f8fafc'
                        },
                        borderRadius: { 'card': '1rem', 'card-lg': '1.25rem', 'xl': '1rem' },
                        boxShadow: {
                            'card': '0 1px 3px 0 rgb(0 0 0 / 0.05), 0 1px 2px -1px rgb(0 0 0 / 0.05)',
                            'card-hover': '0 10px 15px -3px rgb(0 0 0 / 0.08), 0 4px 6px -4px rgb(0 0 0 / 0.05)',
                            'hero': '0 25px 50px -12px rgb(0 0 0 / 0.15)',
                            'float': '0 20px 25px -5px rgb(0 0 0 / 0.08), 0 8px 10px -6px rgb(0 0 0 / 0.05)'
                        },
                        transitionDuration: { '400': '400ms' },
                        animation: {
                            'fade-in': 'fadeIn 0.4s ease-out',
                            'slide-up': 'slideUp 0.4s ease-out',
                            'scale-in': 'scaleIn 0.3s ease-out'
                        },
                        keyframes: {
                            fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                            slideUp: { '0%': { opacity: '0', transform: 'translateY(12px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                            scaleIn: { '0%': { opacity: '0', transform: 'scale(0.96)' }, '100%': { opacity: '1', transform: 'scale(1)' } }
                        }
                    }
                }
            }
        </script>
    @endif
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        [data-ds] { --brand: #7c3aed; --brand-hover: #6d28d9; --brand-light: #ede9fe; --surface: #f8fafc; }
        .btn-brand { background: var(--brand); color: #fff; transition: all 0.2s ease; }
        .btn-brand:hover { background: var(--brand-hover); transform: translateY(-1px); }
        [x-cloak] { display: none !important; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { scrollbar-width: none; -ms-overflow-style: none; }
    </style>
    @stack('styles')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', function() {
            Alpine.store('auth', { open: false, mode: 'login' }); // mode: 'login' | 'register' | 'forgot'
            Alpine.store('search', { open: false });
            Alpine.store('cart', { 
                count: @js(array_sum(session('cart', []))),
                updateCount(newCount) {
                    this.count = newCount;
                }
            });
        });
    </script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col font-sans" data-ds x-data="{}" @if(session('auth_modal')) data-auth-modal="{{ session('auth_modal') }}" @endif>
    @include('components.navbar')

    @include('components.toast-container')
    @include('components.loader-overlay')
    @include('components.confirm-modal')

    <main class="flex-1" role="main">
        @yield('content')
    </main>

    @include('components.footer')

    @guest
    @include('components.auth-modal')
    @endguest

    @include('components.search-modal')

    <button type="button" id="scroll-to-top" class="fixed bottom-6 right-6 z-40 w-12 h-12 rounded-full bg-violet-600 text-white shadow-lg shadow-violet-500/30 hover:bg-violet-700 opacity-0 pointer-events-none transition-all duration-300 flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-violet-500/40 focus:ring-offset-2 hover:scale-110 hover:shadow-xl active:scale-95" aria-label="Volver arriba">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
    </button>

    <script>
        document.addEventListener('alpine:init', function() {
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
