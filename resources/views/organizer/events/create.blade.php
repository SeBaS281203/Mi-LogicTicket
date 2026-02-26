@extends('layouts.organizer')

@section('title', 'Nuevo evento')

@section('content')
<h1 class="text-2xl font-bold text-slate-800 mb-6">Nuevo evento</h1>

<form method="POST" action="{{ route('organizer.events.store') }}" enctype="multipart/form-data" class="space-y-6 max-w-3xl">
    @csrf
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-4">
        <h2 class="font-semibold text-lg">Información general</h2>
        <div>
            <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Título *</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="category_id" class="block text-sm font-medium text-slate-700 mb-1">Categoría *</label>
            <select name="category_id" id="category_id" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Descripción *</label>
            <textarea name="description" id="description" rows="4" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
            @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="event_image" class="block text-sm font-medium text-slate-700 mb-1">Imagen de portada</label>
            <input type="file" name="event_image" id="event_image" accept="image/*" class="w-full rounded-xl border border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            <p class="mt-1 text-xs text-slate-500">JPG, PNG o WebP. Máx. 2 MB.</p>
            @error('event_image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="venue_name" class="block text-sm font-medium text-slate-700 mb-1">Nombre del lugar *</label>
            <input type="text" name="venue_name" id="venue_name" value="{{ old('venue_name') }}" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div>
            <label for="venue_address" class="block text-sm font-medium text-slate-700 mb-1">Dirección *</label>
            <input type="text" name="venue_address" id="venue_address" value="{{ old('venue_address') }}" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="city" class="block text-sm font-medium text-slate-700 mb-1">Ciudad *</label>
                <input type="text" name="city" id="city" value="{{ old('city') }}" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label for="country" class="block text-sm font-medium text-slate-700 mb-1">País</label>
                <input type="text" name="country" id="country" value="{{ old('country', 'Peru') }}" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-slate-700 mb-1">Fecha y hora inicio *</label>
                <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date') }}" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-slate-700 mb-1">Fecha y hora fin</label>
                <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>
        <div>
            <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Estado</label>
            <select name="status" id="status" class="rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Borrador</option>
                <option value="pending_approval" {{ old('status') === 'pending_approval' ? 'selected' : '' }}>Enviar a revisión (publicar)</option>
            </select>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="font-semibold text-lg mb-4">Tipos de entrada</h2>
        <div id="ticket-types" class="space-y-4">
            <div class="ticket-type-row flex flex-wrap gap-4 p-4 bg-slate-50 rounded-lg">
                <input type="text" name="ticket_types[0][name]" placeholder="Nombre (ej. General)" class="flex-1 min-w-[120px] rounded-lg border-slate-300 shadow-sm">
                <input type="number" name="ticket_types[0][price]" placeholder="Precio" step="0.01" min="0" class="w-24 rounded-lg border-slate-300 shadow-sm">
                <input type="number" name="ticket_types[0][quantity]" placeholder="Cantidad" min="1" class="w-24 rounded-lg border-slate-300 shadow-sm">
                <input type="text" name="ticket_types[0][description]" placeholder="Descripción (opcional)" class="flex-1 min-w-[120px] rounded-lg border-slate-300 shadow-sm">
            </div>
        </div>
        <button type="button" id="add-ticket-type" class="mt-2 text-sm text-emerald-600 hover:underline font-medium">+ Añadir otro tipo de entrada</button>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700">Crear evento</button>
        <a href="{{ route('organizer.events.index') }}" class="px-6 py-2.5 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200">Cancelar</a>
    </div>
</form>

<script>
document.getElementById('add-ticket-type').addEventListener('click', function() {
    const container = document.getElementById('ticket-types');
    const index = container.querySelectorAll('.ticket-type-row').length;
    const div = document.createElement('div');
    div.className = 'ticket-type-row flex flex-wrap gap-4 p-4 bg-slate-50 rounded-lg';
    div.innerHTML = `
        <input type="text" name="ticket_types[${index}][name]" placeholder="Nombre" class="flex-1 min-w-[120px] rounded-lg border-slate-300 shadow-sm">
        <input type="number" name="ticket_types[${index}][price]" placeholder="Precio" step="0.01" min="0" class="w-24 rounded-lg border-slate-300 shadow-sm">
        <input type="number" name="ticket_types[${index}][quantity]" placeholder="Cantidad" min="1" class="w-24 rounded-lg border-slate-300 shadow-sm">
        <input type="text" name="ticket_types[${index}][description]" placeholder="Descripción (opcional)" class="flex-1 min-w-[120px] rounded-lg border-slate-300 shadow-sm">
    `;
    container.appendChild(div);
});
</script>
@endsection
