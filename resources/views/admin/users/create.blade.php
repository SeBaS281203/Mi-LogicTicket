@extends('layouts.admin')

@section('title', 'Nuevo usuario')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600">← Volver a usuarios</a>
</div>
<h1 class="text-2xl font-bold text-slate-900 mb-6">Nuevo usuario</h1>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 max-w-xl">
    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nombre</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="role" class="block text-sm font-medium text-slate-700 mb-1">Rol</label>
            <select name="role" id="role" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                <option value="client" {{ old('role') === 'client' ? 'selected' : '' }}>Cliente</option>
                <option value="organizer" {{ old('role') === 'organizer' ? 'selected' : '' }}>Organizador</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrador</option>
            </select>
        </div>
        <div>
            <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Teléfono (opcional)</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
        </div>
        <div>
            <label for="ruc" class="block text-sm font-medium text-slate-700 mb-1">RUC (opcional)</label>
            <input type="text" name="ruc" id="ruc" value="{{ old('ruc') }}" maxlength="20" placeholder="Para organizadores" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Contraseña</label>
            <input type="password" name="password" id="password" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirmar contraseña</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700">Crear usuario</button>
            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200">Cancelar</a>
        </div>
    </form>
</div>
@endsection
