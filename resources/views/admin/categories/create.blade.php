@extends('layouts.admin')

@section('title', 'Nueva categoría')

@section('content')
<h1 class="text-3xl font-bold mb-6">Nueva categoría</h1>
<form method="POST" action="{{ route('admin.categories.store') }}" class="max-w-xl space-y-4 bg-white p-6 rounded-xl shadow-sm border border-slate-200">
    @csrf
    <div>
        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nombre</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="slug" class="block text-sm font-medium text-slate-700 mb-1">Slug (opcional)</label>
        <input type="text" name="slug" id="slug" value="{{ old('slug') }}" placeholder="Se genera automático si se deja vacío" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('slug')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Descripción</label>
        <textarea name="description" id="description" rows="3" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
    </div>
    <div class="flex items-center">
        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
        <label for="is_active" class="ml-2 text-sm text-slate-700">Activa</label>
    </div>
    <div class="flex gap-2">
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Crear</button>
        <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300">Cancelar</a>
    </div>
</form>
@endsection
