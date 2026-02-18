@extends('layouts.admin')

@section('title', 'Reclamo ' . $reclamo->codigo_reclamo)

@section('content')
<div class="mb-6 flex flex-wrap gap-3">
    <a href="{{ route('admin.libro-reclamaciones.index') }}" class="text-slate-600 hover:text-slate-900 text-sm font-medium">← Listado</a>
    <a href="{{ route('libro-reclamaciones.download', $reclamo->codigo_reclamo) }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-800 text-white text-sm font-medium rounded-lg">Descargar PDF</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <h2 class="font-bold text-slate-900">{{ $reclamo->codigo_reclamo }}</h2>
            <span class="px-2 py-1 rounded text-xs {{ $reclamo->estado === 'pendiente' ? 'bg-red-100 text-red-800' : ($reclamo->estado === 'atendido' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-700') }}">{{ ucfirst($reclamo->estado) }}</span>
        </div>
        <div class="p-6 space-y-4 text-sm">
            <p><span class="font-medium text-slate-500">Fecha:</span> {{ $reclamo->created_at->format('d/m/Y H:i') }}</p>
            <p><span class="font-medium text-slate-500">Tipo:</span> {{ ucfirst($reclamo->tipo_reclamo) }}</p>
            <p><span class="font-medium text-slate-500">Documento:</span> {{ $reclamo->tipo_documento }} {{ $reclamo->numero_documento }}</p>
            <p><span class="font-medium text-slate-500">Nombre:</span> {{ $reclamo->nombre_completo }}</p>
            <p><span class="font-medium text-slate-500">Dirección:</span> {{ $reclamo->direccion }}</p>
            <p><span class="font-medium text-slate-500">Teléfono:</span> {{ $reclamo->telefono }}</p>
            <p><span class="font-medium text-slate-500">Email:</span> {{ $reclamo->email }}</p>
            @if($reclamo->evento)
                <p><span class="font-medium text-slate-500">Evento:</span> {{ $reclamo->evento->title }}</p>
            @endif
            <div>
                <span class="font-medium text-slate-500 block mb-1">Descripción:</span>
                <p class="text-slate-700 whitespace-pre-wrap">{{ $reclamo->descripcion }}</p>
            </div>
            @if($reclamo->pedido_consumidor)
                <div>
                    <span class="font-medium text-slate-500 block mb-1">Pedido del consumidor:</span>
                    <p class="text-slate-700 whitespace-pre-wrap">{{ $reclamo->pedido_consumidor }}</p>
                </div>
            @endif
            @if($reclamo->respuesta_empresa)
                <div class="pt-4 border-t border-slate-200">
                    <span class="font-medium text-slate-500 block mb-1">Respuesta de la empresa ({{ $reclamo->fecha_respuesta?->format('d/m/Y H:i') }}):</span>
                    <p class="text-slate-700 whitespace-pre-wrap">{{ $reclamo->respuesta_empresa }}</p>
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
            <h2 class="font-bold text-slate-900">Responder / Cambiar estado</h2>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('admin.libro-reclamaciones.respond', $reclamo) }}" class="space-y-4">
                @csrf
                <div>
                    <label for="respuesta_empresa" class="block text-sm font-medium text-slate-700 mb-1">Respuesta al consumidor *</label>
                    <textarea name="respuesta_empresa" id="respuesta_empresa" rows="5" class="w-full rounded-lg border-slate-200 text-sm" placeholder="Escriba la respuesta...">{{ old('respuesta_empresa', $reclamo->respuesta_empresa) }}</textarea>
                    @error('respuesta_empresa')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="estado" class="block text-sm font-medium text-slate-700 mb-1">Estado</label>
                    <select name="estado" id="estado" class="w-full rounded-lg border-slate-200 text-sm">
                        @foreach(\App\Models\LibroReclamacion::ESTADOS as $e)
                            <option value="{{ $e }}" @if($reclamo->estado === $e) selected @endif>{{ ucfirst($e) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg">Guardar respuesta</button>
            </form>
            <form method="POST" action="{{ route('admin.libro-reclamaciones.update-estado', $reclamo) }}" class="mt-4 pt-4 border-t border-slate-200">
                @csrf
                <label class="block text-sm font-medium text-slate-700 mb-2">Solo cambiar estado (sin respuesta):</label>
                <div class="flex gap-2">
                    <select name="estado" class="rounded-lg border-slate-200 text-sm flex-1">
                        @foreach(\App\Models\LibroReclamacion::ESTADOS as $e)
                            <option value="{{ $e }}" @if($reclamo->estado === $e) selected @endif>{{ ucfirst($e) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white text-sm rounded-lg">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
