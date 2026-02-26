@extends('layouts.guest')

@section('title', 'Contraseña actualizada')

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

        <div class="bg-white rounded-2xl shadow-xl border border-violet-100 p-6 sm:p-8 text-center">
            <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-violet-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h2 class="text-xl sm:text-2xl font-bold text-[#1f2937] mb-2">¡Tu contraseña se cambió correctamente!</h2>
            <p class="text-neutral-500 text-sm mb-6">
                Hemos actualizado la contraseña de tu cuenta de ChiclayoTicket. A partir de ahora deberás usar tu nueva
                contraseña para acceder de forma segura.
            </p>
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center w-full h-11 px-5 py-2.5 bg-brand text-white text-sm font-semibold rounded-xl hover:bg-brand-hover focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2 transition-colors">
                Ir al inicio de sesión
            </a>
        </div>
    </div>
</div>
@endsection

