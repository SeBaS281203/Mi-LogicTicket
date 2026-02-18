<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Iniciar sesión') | {{ config('app.name') }}</title>
    <meta name="description" content="@yield('meta_description', 'Inicia sesión en LogicTicket. Compra entradas para conciertos, deportes y más.')">
    <meta name="robots" content="noindex, nofollow">
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
                    }
                }
            }
        </script>
    @endif
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        [data-ds] { --brand: #00a650; --brand-hover: #009345; --surface: #f5f7fa; --duration-global: 300ms; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen bg-surface text-[#1f2937] antialiased font-sans" data-ds>
    @yield('content')
    @stack('scripts')
</body>
</html>
