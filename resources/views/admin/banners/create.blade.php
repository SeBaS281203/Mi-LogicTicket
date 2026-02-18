@extends('layouts.admin')

@section('title', 'Nuevo banner')

@section('content')
<h1 class="text-3xl font-bold mb-6">Nuevo banner</h1>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-lg">
    <form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Título</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required class="w-full rounded-lg border-slate-300 shadow-sm">
            @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="subtitle" class="block text-sm font-medium text-slate-700 mb-1">Subtítulo</label>
            <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle') }}" class="w-full rounded-lg border-slate-300 shadow-sm">
        </div>
        <div>
            <label for="image" class="block text-sm font-medium text-slate-700 mb-1">Imagen</label>
            <input type="file" name="image" id="image" accept="image/*" class="w-full rounded-lg border-slate-300 shadow-sm">
            @error('image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="link_url" class="block text-sm font-medium text-slate-700 mb-1">URL del enlace</label>
            <input type="url" name="link_url" id="link_url" value="{{ old('link_url') }}" class="w-full rounded-lg border-slate-300 shadow-sm">
        </div>
        <div>
            <label for="link_text" class="block text-sm font-medium text-slate-700 mb-1">Texto del botón</label>
            <input type="text" name="link_text" id="link_text" value="{{ old('link_text') }}" class="w-full rounded-lg border-slate-300 shadow-sm">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="starts_at" class="block text-sm font-medium text-slate-700 mb-1">Vigencia desde</label>
                <input type="date" name="starts_at" id="starts_at" value="{{ old('starts_at') }}" class="w-full rounded-lg border-slate-300 shadow-sm">
            </div>
            <div>
                <label for="ends_at" class="block text-sm font-medium text-slate-700 mb-1">Vigencia hasta</label>
                <input type="date" name="ends_at" id="ends_at" value="{{ old('ends_at') }}" class="w-full rounded-lg border-slate-300 shadow-sm">
            </div>
        </div>
        <div>
            <label for="sort_order" class="block text-sm font-medium text-slate-700 mb-1">Orden</label>
            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="w-full rounded-lg border-slate-300 shadow-sm">
        </div>
        <div class="flex items-center">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-slate-300">
            <label for="is_active" class="ml-2 text-sm text-slate-700">Activo</label>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Crear</button>
            <a href="{{ route('admin.banners.index') }}" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300">Cancelar</a>
        </div>
    </form>
</div>
@endsection
