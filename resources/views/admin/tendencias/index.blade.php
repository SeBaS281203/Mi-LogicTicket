@extends('layouts.admin')

@section('title', 'Tendencias')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Nuestras Tendencias</h1>
    <a href="{{ route('admin.tendencias.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Nueva publicidad</a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-50">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Imagen</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Título</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Enlace</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Orden</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Activo</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Vigencia</th>
                <th class="text-right px-4 py-3 font-medium text-slate-700">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tendencias as $tendencia)
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-3">
                        @if($tendencia->imagen)
                            <img src="{{ asset('storage/' . $tendencia->imagen) }}" alt="" class="h-12 w-16 object-cover rounded">
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $tendencia->titulo ?: '-' }}</td>
                    <td class="px-4 py-3 text-sm truncate max-w-[120px]">{{ $tendencia->link ?: '-' }}</td>
                    <td class="px-4 py-3">{{ $tendencia->orden }}</td>
                    <td class="px-4 py-3">{{ $tendencia->activo ? 'Sí' : 'No' }}</td>
                    <td class="px-4 py-3 text-sm">
                        @if($tendencia->starts_at || $tendencia->ends_at)
                            {{ $tendencia->starts_at?->format('d/m/Y') ?? '-' }} — {{ $tendencia->ends_at?->format('d/m/Y') ?? '-' }}
                        @else
                            Siempre
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.tendencias.edit', $tendencia) }}" class="text-indigo-600 hover:underline text-sm">Editar</a>
                        <form method="POST" action="{{ route('admin.tendencias.destroy', $tendencia) }}" class="inline ml-2" onsubmit="return confirm('¿Eliminar esta publicidad?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-slate-500">No hay publicidades. <a href="{{ route('admin.tendencias.create') }}" class="text-indigo-600 hover:underline">Crear una</a></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $tendencias->links() }}</div>
@endsection
