@extends('layouts.app')

@section('title', 'Acceso no permitido')

@section('content')
@php
    $role = auth()->check() && auth()->user()->roleEnum() ? auth()->user()->roleEnum() : null;
    $dashboardRoute = $role?->dashboardRoute();
@endphp
<div class="min-h-[60vh] flex items-center justify-center px-4">
    <div class="text-center max-w-md animate-fade-in">
        <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-slate-100 flex items-center justify-center">
            <svg class="w-10 h-10 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-800 mb-2">Acceso no permitido</h1>
        <p class="text-slate-600 mb-8">No tienes permiso para ver esta página.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            @if($dashboardRoute)
                <a href="{{ route($dashboardRoute) }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#00a650] text-white font-medium rounded-xl hover:bg-[#009345] transition-colors">
                    Ir a mi panel
                </a>
            @endif
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200 transition-colors">
                Volver al inicio
            </a>
        </div>
    </div>
</div>
@endsection
