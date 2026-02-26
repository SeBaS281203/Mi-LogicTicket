@extends('layouts.organizer')

@section('title', 'Mis eventos')

@section('content')
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <h1 class="text-3xl font-bold text-slate-900">Mis eventos</h1>
    <a href="{{ route('organizer.events.create') }}" class="px-4 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 shadow-sm">Crear evento</a>
</div>


<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50/80">
                <tr>
                    <th class="text-left px-6 py-4 font-semibold text-slate-700">Evento</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-700">Categoría</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-700">Fecha</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-700">Estado</th>
                    <th class="text-right px-6 py-4 font-semibold text-slate-700">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                    <tr class="border-t border-slate-100 hover:bg-slate-50/50">
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $event->title }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $event->category->name }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $event->start_date->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-lg
                                @if($event->status === 'published') bg-emerald-100 text-emerald-700
                                @elseif($event->status === 'pending_approval') bg-amber-100 text-amber-700
                                @elseif($event->status === 'draft') bg-slate-100 text-slate-600
                                @else bg-red-100 text-red-700 @endif">{{ $event->status === 'pending_approval' ? 'Pend. aprobación' : $event->status }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('organizer.events.edit', $event) }}" class="text-emerald-600 hover:text-emerald-700 font-medium text-sm">Editar</a>
                            @if($event->status !== 'cancelled')
                                <x-confirm-form action="{{ route('organizer.events.destroy', $event) }}" method="DELETE" confirmTitle="¿Cancelar este evento?" confirmMessage="Se marcará como cancelado. Esta acción no se puede deshacer." confirmText="Cancelar evento" class="inline ml-3">
                                    <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-sm">Cancelar</button>
                                </x-confirm-form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $events->links() }}</div>
@endsection
