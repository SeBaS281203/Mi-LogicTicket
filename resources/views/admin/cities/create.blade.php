@extends('layouts.admin')

@section('title', 'Nueva ciudad')

@section('content')
<h1 class="text-3xl font-bold mb-6">Nueva ciudad</h1>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-lg">
    <form method="POST" action="{{ route('admin.cities.store') }}" class="space-y-4">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nombre</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="slug" class="block text-sm font-medium text-slate-700 mb-1">Slug (opcional)</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug') }}" class="w-full rounded-lg border-slate-300 shadow-sm">
            @error('slug')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="country" class="block text-sm font-medium text-slate-700 mb-1">País</label>
            <input type="text" name="country" id="country" value="{{ old('country', 'Peru') }}" class="w-full rounded-lg border-slate-300 shadow-sm">
        </div>
        <div>
            <label for="sort_order" class="block text-sm font-medium text-slate-700 mb-1">Orden</label>
            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="w-full rounded-lg border-slate-300 shadow-sm">
        </div>
        <div class="flex items-center">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-slate-300">
            <label for="is_active" class="ml-2 text-sm text-slate-700">Activa</label>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Crear</button>
            <a href="{{ route('admin.cities.index') }}" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300">Cancelar</a>
        </div>
    </form>
</div>
@endsection
