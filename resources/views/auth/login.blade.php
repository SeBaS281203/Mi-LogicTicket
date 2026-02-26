@extends('layouts.guest')

@section('title', 'Iniciar sesión')
@section('meta_description', 'Inicia sesión en ChiclayoTicket y compra entradas para conciertos, deportes y eventos.')

@section('content')
<div class="min-h-screen flex flex-col lg:flex-row">
    {{-- Lado izquierdo: carrusel de imágenes (cambio cada 4 s) + overlay + texto --}}
    <div class="relative lg:w-1/2 min-h-[220px] sm:min-h-[280px] lg:min-h-screen flex items-end lg:items-center justify-center p-6 sm:p-8 lg:p-12 bg-neutral-900 overflow-hidden">
        @php
            $loginImages = [
                ['url' => 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=1200&q=80', 'alt' => 'Conciertos en vivo'],
                ['url' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1200&q=80', 'alt' => 'Eventos y entradas'],
                ['url' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=1200&q=80', 'alt' => 'Experiencias en vivo'],
                ['url' => 'https://images.unsplash.com/photo-1501281668742-f19e2fd1d462?w=1200&q=80', 'alt' => 'Shows y festivales'],
            ];
        @endphp
        @foreach($loginImages as $i => $img)
            <img
                src="{{ $img['url'] }}"
                alt="{{ $img['alt'] }}"
                class="login-hero-img absolute inset-0 w-full h-full object-cover transition-opacity duration-700 {{ $i === 0 ? 'opacity-100 z-0' : 'opacity-0 z-0' }}"
                data-index="{{ $i }}"
                @if($i === 0) fetchpriority="high" @else loading="lazy" @endif
            />
        @endforeach
        <div class="absolute inset-0 bg-gradient-to-t lg:bg-gradient-to-r from-black/85 via-black/50 to-black/30 z-[1] pointer-events-none"></div>
        <div class="relative z-10 text-white text-center lg:text-left max-w-md">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold leading-tight mb-2">
                Vive experiencias inolvidables
            </h1>
            <p class="text-white/90 text-base sm:text-lg">
                Compra tus entradas en segundos.
            </p>
        </div>
    </div>

    {{-- Lado derecho: formulario --}}
    <div class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-12 bg-surface lg:bg-white">
        <div class="w-full max-w-[400px]">
            {{-- Logo / marca --}}
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 text-[#1f2937] font-bold text-xl mb-8">
                <span class="w-10 h-10 rounded-xl bg-brand flex items-center justify-center text-white text-lg font-extrabold shadow-sm">C</span>
                ChiclayoTicket
            </a>

            <div class="bg-white lg:bg-transparent rounded-2xl lg:rounded-none shadow-xl lg:shadow-none border-0 lg:border-0 border-neutral-100 p-6 sm:p-8">
                <h2 class="text-xl sm:text-2xl font-bold text-[#1f2937] mb-1">Iniciar sesión</h2>
                <p class="text-neutral-500 text-sm mb-6">Accede a tu cuenta para continuar</p>

                <form method="POST" action="{{ route('login') }}" id="login-form" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-[#1f2937] mb-1.5">Correo electrónico</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400 pointer-events-none" aria-hidden="true">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="email"
                                placeholder="tu@email.com"
                                class="w-full h-12 pl-11 pr-4 rounded-xl border border-neutral-200 bg-white text-[#1f2937] placeholder-neutral-400 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2 focus:border-brand @error('email') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                            />
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-sm text-red-600" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Contraseña --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-[#1f2937] mb-1.5">Contraseña</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400 pointer-events-none" aria-hidden="true">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="w-full h-12 pl-11 pr-12 rounded-xl border border-neutral-200 bg-white text-[#1f2937] placeholder-neutral-400 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2 focus:border-brand @error('password') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                            />
                            <button
                                type="button"
                                id="toggle-password"
                                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 rounded-lg text-neutral-400 hover:text-neutral-600 hover:bg-neutral-100 transition-colors focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2"
                                aria-label="Mostrar contraseña"
                            >
                                <svg id="icon-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg id="icon-eye-off" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-sm text-red-600" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Recordarme + Olvidé contraseña --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input
                                type="checkbox"
                                name="remember"
                                id="remember"
                                class="w-4 h-4 rounded border-neutral-300 text-brand focus:ring-brand focus:ring-offset-0 transition-colors"
                            />
                            <span class="text-sm text-neutral-600 group-hover:text-[#1f2937]">Recordarme</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-brand hover:text-brand-hover transition-colors">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    {{-- Botón enviar --}}
                    <button
                        type="submit"
                        id="login-submit"
                        class="w-full h-12 flex items-center justify-center gap-2 px-5 py-2.5 bg-brand text-white font-semibold rounded-xl hover:bg-brand-hover focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2 transition-all duration-300 disabled:opacity-70 disabled:pointer-events-none"
                    >
                        <span id="btn-text">Iniciar sesión</span>
                        <span id="btn-loader" class="hidden items-center justify-center">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                </form>

                {{-- Separador: O continúa con --}}
                <div class="relative my-6">
                    <span class="absolute inset-0 flex items-center" aria-hidden="true">
                        <span class="w-full border-t border-neutral-200"></span>
                    </span>
                    <span class="relative flex justify-center text-sm">
                        <span class="bg-white lg:bg-transparent px-3 text-neutral-500">O continúa con</span>
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <a href="#" class="flex items-center justify-center gap-2 h-12 px-4 rounded-xl border border-neutral-200 bg-white text-[#1f2937] font-medium hover:bg-neutral-50 hover:border-neutral-300 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-neutral-200 focus:ring-offset-2" aria-label="Iniciar sesión con Google">
                        <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                        Google
                    </a>
                    <a href="#" class="flex items-center justify-center gap-2 h-12 px-4 rounded-xl border border-neutral-200 bg-white text-[#1f2937] font-medium hover:bg-neutral-50 hover:border-neutral-300 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-neutral-200 focus:ring-offset-2" aria-label="Iniciar sesión con Facebook">
                        <svg class="w-5 h-5" fill="#1877F2" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        Facebook
                    </a>
                </div>
            </div>

            {{-- Crear cuenta --}}
            <p class="mt-8 text-center text-neutral-600 text-sm">
                ¿No tienes cuenta?
                <a href="{{ route('register') }}" class="font-semibold text-brand hover:text-brand-hover transition-colors ml-1">Crear cuenta</a>
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Carrusel del panel izquierdo: cambiar imagen cada 4 segundos
    var heroImages = document.querySelectorAll('.login-hero-img');
    var currentIndex = 0;
    if (heroImages.length > 1) {
        setInterval(function() {
            heroImages[currentIndex].classList.remove('opacity-100');
            heroImages[currentIndex].classList.add('opacity-0');
            currentIndex = (currentIndex + 1) % heroImages.length;
            heroImages[currentIndex].classList.remove('opacity-0');
            heroImages[currentIndex].classList.add('opacity-100');
        }, 4000);
    }

    var form = document.getElementById('login-form');
    var submitBtn = document.getElementById('login-submit');
    var btnText = document.getElementById('btn-text');
    var btnLoader = document.getElementById('btn-loader');
    var passwordInput = document.getElementById('password');
    var toggleBtn = document.getElementById('toggle-password');
    var iconEye = document.getElementById('icon-eye');
    var iconEyeOff = document.getElementById('icon-eye-off');

    if (toggleBtn && passwordInput) {
        toggleBtn.addEventListener('click', function() {
            var type = passwordInput.getAttribute('type');
            if (type === 'password') {
                passwordInput.setAttribute('type', 'text');
                iconEye.classList.add('hidden');
                iconEyeOff.classList.remove('hidden');
                toggleBtn.setAttribute('aria-label', 'Ocultar contraseña');
            } else {
                passwordInput.setAttribute('type', 'password');
                iconEye.classList.remove('hidden');
                iconEyeOff.classList.add('hidden');
                toggleBtn.setAttribute('aria-label', 'Mostrar contraseña');
            }
        });
    }

    if (form && submitBtn && btnText && btnLoader) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            btnText.classList.add('hidden');
            btnLoader.classList.remove('hidden');
            btnLoader.classList.add('flex');
        });
    }
});
</script>
@endpush
@endsection
