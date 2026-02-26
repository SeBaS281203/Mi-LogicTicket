@extends('layouts.organizer')

@section('title', 'Editar evento')

@section('content')
<h1 class="text-2xl font-bold text-slate-800 mb-6">Editar evento</h1>

<form method="POST" action="{{ route('organizer.events.update', $event) }}" enctype="multipart/form-data" class="space-y-6 max-w-3xl">
    @csrf
    @method('PUT')
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-4">
        <h2 class="font-semibold text-lg">Información general</h2>
        <div>
            <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Título *</label>
            <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="category_id" class="block text-sm font-medium text-slate-700 mb-1">Categoría *</label>
            <select name="category_id" id="category_id" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $event->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Descripción *</label>
            <textarea name="description" id="description" rows="4" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $event->description) }}</textarea>
        </div>
        <div>
            <label for="event_image" class="block text-sm font-medium text-slate-700 mb-1">Imagen de portada</label>
            @php $imgUrl = $event->event_image ?? $event->image; @endphp
            @if($imgUrl)
                <div class="mb-2"><img src="{{ str_starts_with($imgUrl, 'http') ? $imgUrl : asset('storage/' . $imgUrl) }}" alt="" class="h-24 object-cover rounded-xl border border-slate-200"></div>
            @endif
            <input type="file" name="event_image" id="event_image" accept="image/*" class="w-full rounded-xl border border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            <p class="mt-1 text-xs text-slate-500">JPG, PNG o WebP. Máx. 2 MB. Dejar vacío para mantener la actual.</p>
            @error('event_image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="venue_name" class="block text-sm font-medium text-slate-700 mb-1">Nombre del lugar *</label>
            <input type="text" name="venue_name" id="venue_name" value="{{ old('venue_name', $event->venue_name) }}" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div>
            <label for="venue_address" class="block text-sm font-medium text-slate-700 mb-1">Dirección *</label>
            <input type="text" name="venue_address" id="venue_address" value="{{ old('venue_address', $event->venue_address) }}" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="city" class="block text-sm font-medium text-slate-700 mb-1">Ciudad *</label>
                <input type="text" name="city" id="city" value="{{ old('city', $event->city) }}" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label for="country" class="block text-sm font-medium text-slate-700 mb-1">País</label>
                <input type="text" name="country" id="country" value="{{ old('country', $event->country) }}" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-slate-700 mb-1">Fecha y hora inicio *</label>
                <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date', $event->start_date?->format('Y-m-d\TH:i')) }}" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-slate-700 mb-1">Fecha y hora fin</label>
                <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date', $event->end_date?->format('Y-m-d\TH:i')) }}" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>
        <div>
            <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Estado</label>
            <select name="status" id="status" class="rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="draft" {{ old('status', $event->status) === 'draft' ? 'selected' : '' }}>Borrador</option>
                <option value="pending_approval" {{ old('status', $event->status) === 'pending_approval' ? 'selected' : '' }}>Enviar a revisión (publicar)</option>
                <option value="cancelled" {{ old('status', $event->status) === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
            </select>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="font-semibold text-lg mb-2">Tipos de entrada</h2>
        <p class="text-sm text-slate-500 mb-4">Puedes aumentar el stock. No se puede reducir por debajo de lo ya vendido.</p>
        <div id="ticket-types" class="space-y-4">
            @foreach($event->ticketTypes as $idx => $tt)
                <div class="ticket-type-row flex flex-wrap gap-4 items-center p-4 bg-slate-50 rounded-lg">
                    <input type="hidden" name="ticket_types[{{ $idx }}][id]" value="{{ $tt->id }}">
                    <input type="text" name="ticket_types[{{ $idx }}][name]" value="{{ old("ticket_types.{$idx}.name", $tt->name) }}" placeholder="Nombre" class="flex-1 min-w-[120px] rounded-lg border-slate-300 shadow-sm">
                    <input type="number" name="ticket_types[{{ $idx }}][price]" value="{{ old("ticket_types.{$idx}.price", $tt->price) }}" step="0.01" min="0" class="w-24 rounded-lg border-slate-300 shadow-sm">
                    <div class="flex items-center gap-1">
                        <input type="number" name="ticket_types[{{ $idx }}][quantity]" value="{{ old("ticket_types.{$idx}.quantity", $tt->quantity) }}" min="{{ $tt->quantity_sold }}" class="w-24 rounded-lg border-slate-300 shadow-sm" title="Mínimo: {{ $tt->quantity_sold }} (vendidos)">
                        <span class="text-xs text-slate-500 whitespace-nowrap">(vend: {{ $tt->quantity_sold }})</span>
                    </div>
                    <input type="text" name="ticket_types[{{ $idx }}][description]" value="{{ old("ticket_types.{$idx}.description", $tt->description) }}" placeholder="Descripción" class="flex-1 min-w-[120px] rounded-lg border-slate-300 shadow-sm">
                </div>
            @endforeach
        </div>
        <button type="button" id="add-ticket-type" class="mt-2 text-sm text-emerald-600 hover:underline font-medium">+ Añadir otro tipo de entrada</button>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700">Guardar</button>
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
        <input type="text" name="ticket_types[${index}][description]" placeholder="Descripción" class="flex-1 min-w-[120px] rounded-lg border-slate-300 shadow-sm">
    `;
    container.appendChild(div);
});
</script>
@endsection
