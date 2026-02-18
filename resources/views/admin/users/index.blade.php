@extends('layouts.admin')

@section('title', 'Usuarios')

@section('content')
<h1 class="text-3xl font-bold mb-6">Usuarios</h1>

<form method="GET" class="mb-4 flex gap-2 flex-wrap">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por nombre o email" class="rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    <select name="role" class="rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">Todos los roles</option>
        <option value="admin" @selected(request('role') === 'admin')>Admin</option>
        <option value="organizer" @selected(request('role') === 'organizer')>Organizador</option>
        <option value="client" @selected(request('role') === 'client')>Cliente</option>
    </select>
    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Filtrar</button>
</form>

@if(session('success'))
    <p class="text-green-600 mb-4">{{ session('success') }}</p>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-50">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Nombre</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Email</th>
                <th class="text-left px-4 py-3 font-medium text-slate-700">Rol</th>
                <th class="text-right px-4 py-3 font-medium text-slate-700">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-3">{{ $user->name }}</td>
                    <td class="px-4 py-3">{{ $user->email }}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded bg-slate-100">{{ $user->role }}</span></td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:underline text-sm">Editar</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $users->links() }}</div>
@endsection
