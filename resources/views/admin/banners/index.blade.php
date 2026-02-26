@extends('layouts.admin')

@section('title', 'Banners')

@section('content')
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
    <div>
        <h1 class="text-3xl font-bold">Slider de eventos destacados</h1>
        <p class="text-sm text-slate-500 mt-1">Aqui el administrador puede cambiar o agregar imagenes para el banner principal de inicio.</p>
    </div>
    <a href="{{ route('admin.banners.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-center">Nuevo banner</a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
    <h2 class="text-lg font-semibold text-slate-900 mb-1">Carga rapida de imagenes</h2>
    <p class="text-sm text-slate-500 mb-5">Sube varias imagenes de una sola vez para ampliar el carrusel principal.</p>

    <form method="POST" action="{{ route('admin.banners.bulk-store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label for="images" class="block text-sm font-medium text-slate-700 mb-1">Imagenes del slider (maximo 10)</label>
            <input type="file" name="images[]" id="images" accept="image/*" multiple required class="w-full rounded-lg border-slate-300 shadow-sm">
            @error('images')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            @error('images.*')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="title_prefix" class="block text-sm font-medium text-slate-700 mb-1">Prefijo de titulo</label>
                <input type="text" name="title_prefix" id="title_prefix" value="{{ old('title_prefix', 'Banner destacado') }}" class="w-full rounded-lg border-slate-300 shadow-sm">
            </div>
            <div>
                <label for="subtitle" class="block text-sm font-medium text-slate-700 mb-1">Subtitulo (opcional)</label>
                <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle') }}" class="w-full rounded-lg border-slate-300 shadow-sm">
            </div>
            <div>
                <label for="link_url" class="block text-sm font-medium text-slate-700 mb-1">URL del enlace (opcional)</label>
                <input type="url" name="link_url" id="link_url" value="{{ old('link_url') }}" class="w-full rounded-lg border-slate-300 shadow-sm">
            </div>
            <div>
                <label for="link_text" class="block text-sm font-medium text-slate-700 mb-1">Texto del boton (opcional)</label>
                <input type="text" name="link_text" id="link_text" value="{{ old('link_text') }}" class="w-full rounded-lg border-slate-300 shadow-sm">
            </div>
            <div>
                <label for="starts_at" class="block text-sm font-medium text-slate-700 mb-1">Vigencia desde (opcional)</label>
                <input type="date" name="starts_at" id="starts_at" value="{{ old('starts_at') }}" class="w-full rounded-lg border-slate-300 shadow-sm">
            </div>
            <div>
                <label for="ends_at" class="block text-sm font-medium text-slate-700 mb-1">Vigencia hasta (opcional)</label>
                <input type="date" name="ends_at" id="ends_at" value="{{ old('ends_at') }}" class="w-full rounded-lg border-slate-300 shadow-sm">
            </div>
        </div>

        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-slate-300">
            Activar banners al crear
        </label>

        <div class="pt-2">
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Agregar imagenes al slider</button>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[900px]">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-slate-700">Imagen</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-700">Titulo</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-700">Enlace</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-700">Orden</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-700">Activo</th>
                    <th class="text-left px-4 py-3 font-medium text-slate-700">Vigencia</th>
                    <th class="text-right px-4 py-3 font-medium text-slate-700">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-3">
                            @if($banner->image_url)
                                <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="h-14 w-24 object-cover rounded-lg border border-slate-200">
                            @else
                                <span class="text-xs text-slate-400">Sin imagen</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $banner->title }}</td>
                        <td class="px-4 py-3 text-sm">
                            @if($banner->link_url)
                                <a href="{{ $banner->link_url }}" target="_blank" class="text-indigo-600 hover:underline">Abrir enlace</a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $banner->sort_order }}</td>
                        <td class="px-4 py-3">{{ $banner->is_active ? 'Si' : 'No' }}</td>
                        <td class="px-4 py-3 text-sm">
                            @if($banner->starts_at || $banner->ends_at)
                                {{ $banner->starts_at?->format('d/m/Y') ?? '-' }} - {{ $banner->ends_at?->format('d/m/Y') ?? '-' }}
                            @else
                                Siempre
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.banners.edit', $banner) }}" class="text-indigo-600 hover:underline text-sm">Editar / cambiar imagen</a>
                            <x-confirm-form action="{{ route('admin.banners.destroy', $banner) }}" method="DELETE" confirmTitle="Eliminar banner?" confirmMessage="Esta accion no se puede deshacer." confirmText="Eliminar" class="inline ml-2">
                                <button type="submit" class="text-red-600 hover:underline text-sm">Eliminar</button>
                            </x-confirm-form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-slate-500">No hay banners registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $banners->links() }}</div>
@endsection
