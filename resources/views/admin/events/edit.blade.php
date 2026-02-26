@extends('layouts.admin')

@section('title', 'Editar evento')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.events.show', $event) }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600">← Volver al evento</a>
</div>
<h1 class="text-2xl font-bold text-slate-900 mb-6">Editar evento</h1>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 max-w-2xl">
    <form method="POST" action="{{ route('admin.events.update', $event) }}" class="space-y-5">
        @csrf
        @method('PUT')
        <div>
            <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Título *</label>
            <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label for="category_id" class="block text-sm font-medium text-slate-700 mb-1">Categoría *</label>
                <select name="category_id" id="category_id" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $event->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Estado *</label>
                <select name="status" id="status" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    <option value="draft" {{ old('status', $event->status) === 'draft' ? 'selected' : '' }}>Borrador</option>
                    <option value="pending_approval" {{ old('status', $event->status) === 'pending_approval' ? 'selected' : '' }}>Pendiente aprobación</option>
                    <option value="published" {{ old('status', $event->status) === 'published' ? 'selected' : '' }}>Publicado</option>
                    <option value="cancelled" {{ old('status', $event->status) === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Descripción</label>
            <textarea name="description" id="description" rows="4" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">{{ old('description', $event->description) }}</textarea>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label for="venue_name" class="block text-sm font-medium text-slate-700 mb-1">Lugar</label>
                <input type="text" name="venue_name" id="venue_name" value="{{ old('venue_name', $event->venue_name) }}" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
            <div>
                <label for="city" class="block text-sm font-medium text-slate-700 mb-1">Ciudad *</label>
                <input type="text" name="city" id="city" value="{{ old('city', $event->city) }}" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                @error('city')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label for="venue_address" class="block text-sm font-medium text-slate-700 mb-1">Dirección</label>
            <input type="text" name="venue_address" id="venue_address" value="{{ old('venue_address', $event->venue_address) }}" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-slate-700 mb-1">Fecha inicio *</label>
                <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date', $event->start_date?->format('Y-m-d\TH:i')) }}" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-slate-700 mb-1">Fecha fin</label>
                <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date', $event->end_date?->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700">Guardar cambios</button>
            <a href="{{ route('admin.events.show', $event) }}" class="px-5 py-2.5 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200">Cancelar</a>
            <form method="POST" action="{{ route('admin.events.destroy', $event) }}" class="ml-auto" onsubmit="return confirm('¿Cancelar este evento? Se marcará como cancelado.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-5 py-2.5 bg-red-100 text-red-700 font-medium rounded-xl hover:bg-red-200">Cancelar evento</button>
            </form>
        </div>
    </form>
</div>
@endsection
