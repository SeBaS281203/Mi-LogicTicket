@extends('layouts.admin')

@section('title', 'Ciudades')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Ciudades</h1>
    <a href="{{ route('admin.cities.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Nueva ciudad</a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-50">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Nombre</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Slug</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">País</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Orden</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Activa</th>
                <th class="text-right px-4 py-3 font-medium text-slate-700">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cities as $city)
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-3">{{ $city->name }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $city->slug }}</td>
                    <td class="px-4 py-3">{{ $city->country }}</td>
                    <td class="px-4 py-3">{{ $city->sort_order }}</td>
                    <td class="px-4 py-3">{{ $city->is_active ? 'Sí' : 'No' }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.cities.edit', $city) }}" class="text-indigo-600 hover:underline text-sm">Editar</a>
                        <form method="POST" action="{{ route('admin.cities.destroy', $city) }}" class="inline ml-2" onsubmit="return confirm('¿Eliminar ciudad?')">
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
<div class="mt-4">{{ $cities->links() }}</div>
@endsection
