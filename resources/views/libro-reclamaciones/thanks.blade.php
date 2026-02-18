@extends('layouts.app')

@section('title', 'Registro recibido - Libro de Reclamaciones')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-2xl shadow-lg border border-neutral-100 overflow-hidden">
        <div class="bg-gradient-to-br from-[#00a650] to-[#008f47] text-white px-6 py-5 flex items-center justify-center gap-3">
            <span class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-[#00a650] text-xl font-extrabold shadow">L</span>
            <div>
                <p class="text-white/90 text-xs uppercase tracking-wider">LogicTicket</p>
                <p class="font-bold">Libro de Reclamaciones</p>
            </div>
        </div>
        <div class="p-8 text-center">
        <div class="w-16 h-16 rounded-full bg-[#00a650]/20 flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8 text-[#00a650]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h1 class="text-xl font-bold text-neutral-900 mb-2">Registro recibido correctamente</h1>
        <p class="text-neutral-600 mb-4">Hemos registrado su {{ $reclamo->tipo_reclamo === 'reclamo' ? 'reclamo' : 'queja' }} en nuestro Libro de Reclamaciones Virtual.</p>
        <p class="text-lg font-semibold text-[#00a650] mb-6">Código: {{ $reclamo->codigo_reclamo }}</p>
        <p class="text-sm text-neutral-500 mb-6">Se ha enviado una constancia a <strong>{{ $reclamo->email }}</strong>. Puede descargar la constancia en PDF a continuación.</p>
        <a href="{{ route('libro-reclamaciones.download', $reclamo->codigo_reclamo) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#00a650] hover:bg-[#009345] text-white font-semibold rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Descargar constancia (PDF)
        </a>
        <p class="mt-8">
            <a href="{{ route('home') }}" class="text-[#00a650] hover:underline font-medium">Volver al inicio</a>
        </p>
        </div>
    </div>
</div>
@endsection
