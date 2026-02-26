@extends('layouts.admin')

@section('title', 'Editar tendencia')

@section('content')
<h1 class="text-3xl font-bold mb-6">Editar publicidad - Nuestras Tendencias</h1>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-lg">
    @if($tendencia->imagen)
        <p class="text-sm text-slate-500 mb-2">Imagen actual:</p>
        <img src="{{ asset('storage/' . $tendencia->imagen) }}" alt="" class="h-24 object-cover rounded-lg mb-4">
    @endif
    <form method="POST" action="{{ route('admin.tendencias.update', $tendencia) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label for="titulo" class="block text-sm font-medium text-slate-700 mb-1">Título (opcional)</label>
            <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $tendencia->titulo) }}" class="w-full rounded-lg border-slate-300 shadow-sm">
        </div>
        <div>
            <label for="imagen" class="block text-sm font-medium text-slate-700 mb-1">Nueva imagen (opcional)</label>
            <input type="file" name="imagen" id="imagen" accept="image/*" class="w-full rounded-lg border-slate-300 shadow-sm">
            @error('imagen')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="link" class="block text-sm font-medium text-slate-700 mb-1">URL del enlace</label>
            <input type="url" name="link" id="link" value="{{ old('link', $tendencia->link) }}" class="w-full rounded-lg border-slate-300 shadow-sm" placeholder="https://">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="starts_at" class="block text-sm font-medium text-slate-700 mb-1">Vigencia desde</label>
                <input type="date" name="starts_at" id="starts_at" value="{{ old('starts_at', $tendencia->starts_at?->format('Y-m-d')) }}" class="w-full rounded-lg border-slate-300 shadow-sm">
            </div>
            <div>
                <label for="ends_at" class="block text-sm font-medium text-slate-700 mb-1">Vigencia hasta</label>
                <input type="date" name="ends_at" id="ends_at" value="{{ old('ends_at', $tendencia->ends_at?->format('Y-m-d')) }}" class="w-full rounded-lg border-slate-300 shadow-sm">
            </div>
        </div>
        <div>
            <label for="orden" class="block text-sm font-medium text-slate-700 mb-1">Orden</label>
            <input type="number" name="orden" id="orden" value="{{ old('orden', $tendencia->orden) }}" min="0" class="w-full rounded-lg border-slate-300 shadow-sm">
        </div>
        <div class="flex items-center">
            <input type="checkbox" name="activo" id="activo" value="1" {{ old('activo', $tendencia->activo) ? 'checked' : '' }} class="rounded border-slate-300">
            <label for="activo" class="ml-2 text-sm text-slate-700">Activo</label>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Guardar</button>
            <a href="{{ route('admin.tendencias.index') }}" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300">Cancelar</a>
        </div>
    </form>
</div>
@endsection
