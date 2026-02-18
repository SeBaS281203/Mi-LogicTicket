@props([])

@php
    $categories = [
        ['name' => 'Conciertos', 'slug' => 'conciertos'],
        ['name' => 'Cultura', 'slug' => 'conferencias'],
        ['name' => 'Teatro', 'slug' => 'teatro'],
        ['name' => 'Deportes', 'slug' => 'deportes'],
        ['name' => 'Comida & Bebidas', 'slug' => 'gastronomia'],
        ['name' => 'Donación', 'slug' => 'conferencias'],
        ['name' => 'Stand Up', 'slug' => 'fiestas'],
    ];
@endphp

<div
    x-data="{ showPassword: false, showPasswordConfirm: false }"
    x-show="$store.auth.open"
    x-cloak
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @keydown.escape.window="$store.auth.open = false"
    class="fixed inset-0 z-[100] flex items-center justify-center p-0 sm:p-4"
    role="dialog"
    aria-modal="true"
    aria-labelledby="auth-modal-title"
>
    {{-- Overlay: oscuro + blur --}}
    <div
        class="absolute inset-0 bg-black/60 backdrop-blur-sm"
        @click="$store.auth.open = false"
    ></div>

    {{-- Modal box: animación scale + contenido --}}
    <div
        x-show="$store.auth.open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative w-full max-w-5xl max-h-[90vh] sm:max-h-[85vh] flex flex-col lg:flex-row bg-white rounded-2xl sm:rounded-3xl shadow-2xl overflow-hidden"
        @click.stop
    >
        {{-- Botón cerrar X --}}
        <button
            type="button"
            @click="$store.auth.open = false"
            class="absolute top-4 right-4 z-20 w-10 h-10 flex items-center justify-center rounded-full text-neutral-500 hover:text-neutral-700 hover:bg-neutral-100 transition-colors focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2"
            aria-label="Cerrar"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        {{-- Lado izquierdo (desktop): imagen + overlay verde + texto + categorías --}}
        <div class="hidden lg:flex lg:w-1/2 relative min-h-[200px] bg-neutral-900">
            <img
                src="https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=800&q=80"
                alt="Eventos"
                class="absolute inset-0 w-full h-full object-cover"
            />
            <div class="absolute inset-0 bg-gradient-to-t lg:bg-gradient-to-r from-[#00a650]/90 via-[#00a650]/60 to-[#00a650]/40"></div>
            <div class="relative z-10 flex flex-col justify-between p-8 w-full">
                <div>
                    <h2 class="text-2xl xl:text-3xl font-bold text-white leading-tight mb-2">
                        ¡Encuentra tu próximo plan!
                    </h2>
                    <p class="text-white/90 text-sm xl:text-base mb-6">
                        Explora nuestras categorías
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($categories as $cat)
                            <a
                                href="{{ route('events.index', ['category_slug' => $cat['slug']]) }}"
                                class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium text-white/95 bg-white/15 backdrop-blur-sm border border-white/20 hover:bg-white/25 transition-all duration-300"
                            >
                                {{ $cat['name'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
                {{-- Indicadores tipo slider --}}
                <div class="flex gap-2 mt-6">
                    <span class="w-2 h-2 rounded-full bg-white/80"></span>
                    <span class="w-2 h-2 rounded-full bg-white/40"></span>
                    <span class="w-2 h-2 rounded-full bg-white/40"></span>
                </div>
            </div>
        </div>

        {{-- Lado derecho: formulario (scroll interno en mobile) --}}
        <div class="flex-1 overflow-y-auto flex flex-col min-h-0">
            {{-- Mobile: imagen arriba --}}
            <div class="lg:hidden relative h-32 flex-shrink-0">
                <img src="https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=800&q=80" alt="" class="absolute inset-0 w-full h-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-t from-[#00a650]/80 to-transparent"></div>
            </div>

            <div class="flex-1 p-6 sm:p-8 flex flex-col">
                {{-- Logo --}}
                <a href="{{ route('home') }}" @click="$store.auth.open = false" class="inline-flex items-center gap-2.5 text-[#1f2937] font-bold text-xl mb-6 self-center lg:self-auto">
                    <span class="w-10 h-10 rounded-xl bg-[#00a650] flex items-center justify-center text-white text-lg font-extrabold">L</span>
                    LogicTicket
                </a>

                {{-- Título dinámico + switch login/registro --}}
                <div class="mb-6">
                    <h1 id="auth-modal-title" class="text-xl sm:text-2xl font-bold text-[#1f2937]" x-text="$store.auth.mode === 'login' ? '¡Bienvenido a LogicTicket!' : 'Crea tu cuenta'"></h1>
                    <p class="mt-2 text-sm text-neutral-500">
                        <template x-if="$store.auth.mode === 'login'">
                            <span>¿No tienes cuenta? <button type="button" @click="$store.auth.mode = 'register'" class="font-semibold text-[#00a650] hover:text-[#009345] transition-colors">Regístrate aquí</button></span>
                        </template>
                        <template x-if="$store.auth.mode === 'register'">
                            <span>¿Ya tienes cuenta? <button type="button" @click="$store.auth.mode = 'login'" class="font-semibold text-[#00a650] hover:text-[#009345] transition-colors">Inicia sesión</button></span>
                        </template>
                    </p>
                </div>

                {{-- Formulario LOGIN --}}
                <div x-show="$store.auth.mode === 'login'" x-cloak class="space-y-4">
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="auth-email" class="block text-sm font-medium text-[#1f2937] mb-1.5">Correo electrónico</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></span>
                                <input type="email" name="email" id="auth-email" value="{{ old('email') }}" required autocomplete="email" placeholder="tu@email.com"
                                    class="w-full h-11 pl-10 pr-4 rounded-lg border border-neutral-200 bg-white text-[#1f2937] placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-[#00a650] focus:border-[#00a650] transition-all @error('email') border-red-500 @enderror" />
                            </div>
                            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="auth-password" class="block text-sm font-medium text-[#1f2937] mb-1.5">Contraseña</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></span>
                                <input :type="showPassword ? 'text' : 'password'" name="password" id="auth-password" required autocomplete="current-password" placeholder="••••••••"
                                    class="w-full h-11 pl-10 pr-11 rounded-lg border border-neutral-200 bg-white text-[#1f2937] placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-[#00a650] focus:border-[#00a650] transition-all @error('password') border-red-500 @enderror" />
                                <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-neutral-600" :aria-label="showPassword ? 'Ocultar' : 'Mostrar contraseña'">
                                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                            @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            <p class="mt-1.5">
                                <a href="{{ route('password.request') }}" class="text-sm text-[#00a650] hover:text-[#009345] transition-colors">¿Contraseña olvidada?</a>
                            </p>
                        </div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-neutral-300 text-[#00a650] focus:ring-[#00a650]">
                            <span class="text-sm text-neutral-600">Recordarme</span>
                        </label>
                        <button type="submit" class="w-full h-12 rounded-full bg-[#00a650] text-white font-semibold hover:bg-[#009345] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#00a650] focus:ring-offset-2">
                            Ingresar
                        </button>
                    </form>
                </div>

                {{-- Formulario REGISTRO --}}
                <div x-show="$store.auth.mode === 'register'" x-cloak class="space-y-4" style="display: none;">
                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="auth-name" class="block text-sm font-medium text-[#1f2937] mb-1.5">Nombre completo</label>
                            <input type="text" name="name" id="auth-name" value="{{ old('name') }}" required autocomplete="name" placeholder="Tu nombre"
                                class="w-full h-11 px-4 rounded-lg border border-neutral-200 bg-white text-[#1f2937] placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-[#00a650] focus:border-[#00a650] transition-all @error('name') border-red-500 @enderror" />
                            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="auth-reg-email" class="block text-sm font-medium text-[#1f2937] mb-1.5">Correo electrónico</label>
                            <input type="email" name="email" id="auth-reg-email" value="{{ old('email') }}" required autocomplete="email" placeholder="tu@email.com"
                                class="w-full h-11 px-4 rounded-lg border border-neutral-200 bg-white text-[#1f2937] placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-[#00a650] focus:border-[#00a650] transition-all @error('email') border-red-500 @enderror" />
                            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="auth-reg-password" class="block text-sm font-medium text-[#1f2937] mb-1.5">Contraseña</label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" name="password" id="auth-reg-password" required autocomplete="new-password" placeholder="••••••••"
                                    class="w-full h-11 pl-4 pr-11 rounded-lg border border-neutral-200 bg-white text-[#1f2937] placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-[#00a650] focus:border-[#00a650] transition-all @error('password') border-red-500 @enderror" />
                                <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-neutral-600" :aria-label="showPassword ? 'Ocultar' : 'Mostrar'">
                                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                            @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="auth-password-confirm" class="block text-sm font-medium text-[#1f2937] mb-1.5">Confirmar contraseña</label>
                            <div class="relative">
                                <input :type="showPasswordConfirm ? 'text' : 'password'" name="password_confirmation" id="auth-password-confirm" required autocomplete="new-password" placeholder="••••••••"
                                    class="w-full h-11 pl-4 pr-11 rounded-lg border border-neutral-200 bg-white text-[#1f2937] placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-[#00a650] focus:border-[#00a650] transition-all" />
                                <button type="button" @click="showPasswordConfirm = !showPasswordConfirm" class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-neutral-600" :aria-label="showPasswordConfirm ? 'Ocultar' : 'Mostrar'">
                                    <svg x-show="!showPasswordConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="showPasswordConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="role" value="client" />
                        <input type="hidden" name="phone" value="" />
                        <button type="submit" class="w-full h-12 rounded-full bg-[#00a650] text-white font-semibold hover:bg-[#009345] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#00a650] focus:ring-offset-2">
                            Crear cuenta
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
