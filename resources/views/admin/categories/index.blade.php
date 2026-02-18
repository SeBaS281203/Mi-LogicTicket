@extends('layouts.admin')

@section('title', 'Categorías')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Categorías</h1>
    <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Nueva categoría</a>
</div>

@if(session('success'))
    <p class="text-green-600 mb-4">{{ session('success') }}</p>
@endif
@if(session('error'))
    <p class="text-red-600 mb-4">{{ session('error') }}</p>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-50">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Nombre</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Slug</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Eventos</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Activa</th>
                <th class="text-right px-4 py-3 font-medium text-slate-700">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $cat)
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-3">{{ $cat->name }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $cat->slug }}</td>
                    <td class="px-4 py-3">{{ $cat->events_count }}</td>
                    <td class="px-4 py-3">{{ $cat->is_active ? 'Sí' : 'No' }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.categories.edit', $cat) }}" class="text-indigo-600 hover:underline text-sm">Editar</a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" class="inline ml-2" onsubmit="return confirm('¿Eliminar categoría?')">
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
<div class="mt-4">{{ $categories->links() }}</div>
@endsection
