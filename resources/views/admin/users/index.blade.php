@extends('layouts.admin')

@section('title', 'Usuarios')

@section('content')
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <h1 class="text-3xl font-bold text-slate-900">Usuarios</h1>
    <a href="{{ route('admin.users.create') }}" class="px-4 py-2.5 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 shadow-sm">Crear usuario</a>
</div>

<form method="GET" class="mb-6 flex gap-3 flex-wrap">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por nombre o email" class="rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 px-4 py-2">
    <select name="role" class="rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 px-4 py-2">
        <option value="">Todos los roles</option>
        <option value="admin" @selected(request('role') === 'admin')>Admin</option>
        <option value="organizer" @selected(request('role') === 'organizer')>Organizador</option>
        <option value="client" @selected(request('role') === 'client')>Cliente</option>
    </select>
    <button type="submit" class="px-4 py-2 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200">Filtrar</button>
</form>

@if(session('success'))
    <p class="mb-4 text-emerald-600 font-medium">{{ session('success') }}</p>
@endif

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50/80">
                <tr>
                    <th class="text-left px-6 py-4 font-semibold text-slate-700">Nombre</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-700">Email</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-700">Rol</th>
                    <th class="text-right px-6 py-4 font-semibold text-slate-700">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="border-t border-slate-100 hover:bg-slate-50/50">
                        <td class="px-6 py-4 text-slate-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                        <td class="px-6 py-4"><span class="px-2.5 py-1 text-xs font-medium rounded-lg {{ $user->role === 'admin' ? 'bg-indigo-100 text-indigo-700' : ($user->role === 'organizer' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-700') }}">{{ $user->role }}</span></td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-700 font-medium text-sm mr-4">Editar</a>
                            @if(auth()->id() !== $user->id)
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('¿Eliminar este usuario? No se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-sm">Eliminar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $users->withQueryString()->links() }}</div>
@endsection
