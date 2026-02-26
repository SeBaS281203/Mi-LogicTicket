@extends('layouts.admin')

@section('title', 'Editar usuario')

@section('content')
<h1 class="text-3xl font-bold mb-6">Editar usuario</h1>
<form method="POST" action="{{ route('admin.users.update', $user) }}" class="max-w-xl space-y-4 bg-white p-6 rounded-xl shadow-sm border border-slate-200">
    @csrf
    @method('PUT')
    <div>
        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nombre</label>
        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="role" class="block text-sm font-medium text-slate-700 mb-1">Rol</label>
        <select name="role" id="role" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="client" {{ old('role', $user->role) === 'client' ? 'selected' : '' }}>Cliente</option>
            <option value="organizer" {{ old('role', $user->role) === 'organizer' ? 'selected' : '' }}>Organizador</option>
            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
    </div>
    <div>
        <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Teléfono</label>
        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>
    <div>
        <label for="ruc" class="block text-sm font-medium text-slate-700 mb-1">RUC</label>
        <input type="text" name="ruc" id="ruc" value="{{ old('ruc', $user->ruc) }}" maxlength="20" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Para organizadores">
    </div>
    <div>
        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Nueva contraseña (dejar vacío para no cambiar)</label>
        <input type="password" name="password" id="password" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirmar contraseña</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>
    <div class="flex gap-2">
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Guardar</button>
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300">Cancelar</a>
    </div>
</form>
@endsection
