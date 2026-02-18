@extends('layouts.admin')

@section('title', 'Libro de Reclamaciones - Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Libro de Reclamaciones</h1>
    <p class="text-slate-600 text-sm mt-1">Métricas y acceso al listado</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <p class="text-slate-500 text-sm font-medium">Reclamos este mes</p>
        <p class="text-2xl font-bold text-slate-900 mt-1">{{ $este_mes }}</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <p class="text-slate-500 text-sm font-medium">Pendientes de respuesta</p>
        <p class="text-2xl font-bold text-red-600 mt-1">{{ $pendientes }}</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <p class="text-slate-500 text-sm font-medium">Tiempo promedio de respuesta (días)</p>
        <p class="text-2xl font-bold text-slate-900 mt-1">{{ $tiempo_promedio_dias }}</p>
    </div>
</div>

<div>
    <a href="{{ route('admin.libro-reclamaciones.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg">Ver listado de reclamos →</a>
</div>
@endsection
