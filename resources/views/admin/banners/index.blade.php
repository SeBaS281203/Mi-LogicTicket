@extends('layouts.admin')

@section('title', 'Banners')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Banners promocionales</h1>
    <a href="{{ route('admin.banners.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Nuevo banner</a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-50">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Título</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Enlace</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Orden</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Activo</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Vigencia</th>
                <th class="text-right px-4 py-3 font-medium text-slate-700">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($banners as $banner)
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-3">{{ $banner->title }}</td>
                    <td class="px-4 py-3 text-sm">{{ $banner->link_url ?: '-' }}</td>
                    <td class="px-4 py-3">{{ $banner->sort_order }}</td>
                    <td class="px-4 py-3">{{ $banner->is_active ? 'Sí' : 'No' }}</td>
                    <td class="px-4 py-3 text-sm">
                        @if($banner->starts_at || $banner->ends_at)
                            {{ $banner->starts_at?->format('d/m/Y') ?? '-' }} — {{ $banner->ends_at?->format('d/m/Y') ?? '-' }}
                        @else
                            Siempre
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.banners.edit', $banner) }}" class="text-indigo-600 hover:underline text-sm">Editar</a>
                        <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" class="inline ml-2" onsubmit="return confirm('¿Eliminar banner?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $banners->links() }}</div>
@endsection
