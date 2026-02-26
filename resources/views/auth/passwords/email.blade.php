@extends('layouts.guest')

@section('title', 'Recuperar contraseña')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-12 bg-surface">
    <div class="w-full max-w-[400px]">
        {{-- Logo / marca --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 text-[#1f2937] font-bold text-xl">
                <span class="w-10 h-10 rounded-xl bg-brand flex items-center justify-center text-white text-lg font-extrabold shadow-sm">C</span>
                ChiclayoTicket
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-neutral-100 p-6 sm:p-8">
            <h2 class="text-xl sm:text-2xl font-bold text-[#1f2937] mb-1">Recuperar contraseña</h2>
            <p class="text-neutral-500 text-sm mb-6">Ingresa tu correo y te enviaremos un enlace para restablecer tu contraseña.</p>

            @if(session('status'))
                <div class="mb-6 p-4 bg-violet-50 border border-violet-100 text-violet-700 rounded-xl text-sm flex items-start gap-3">
                    <svg class="w-5 h-5 text-violet-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" id="forgot-password-form" class="space-y-5">
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

                {{-- Botón enviar --}}
                <button
                    type="submit"
                    id="submit-btn"
                    class="w-full h-12 flex items-center justify-center gap-2 px-5 py-2.5 bg-brand text-white font-semibold rounded-xl hover:bg-brand-hover focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2 transition-all duration-300 disabled:opacity-70 disabled:pointer-events-none"
                >
                    <span id="btn-text">Enviar enlace de recuperación</span>
                    <span id="btn-loader" class="hidden items-center justify-center">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-neutral-100 text-center">
                <a href="{{ route('login') }}" class="text-sm font-medium text-neutral-500 hover:text-brand transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Volver al inicio de sesión
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('forgot-password-form');
    var submitBtn = document.getElementById('submit-btn');
    var btnText = document.getElementById('btn-text');
    var btnLoader = document.getElementById('btn-loader');

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

