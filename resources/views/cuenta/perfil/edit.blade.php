@extends('layouts.cuenta')

@section('title', 'Mi perfil')

@section('content')
<h1 class="text-3xl font-bold text-slate-900 mb-2">Mi perfil</h1>
<p class="text-slate-600 mb-8">Actualiza tu información personal y contraseña.</p>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 max-w-xl">
    <form method="POST" action="{{ route('cuenta.perfil.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Datos personales</h2>
            <div class="space-y-5">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nombre</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Correo electrónico</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Teléfono</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20" placeholder="Opcional">
            </div>
            @if($user->isOrganizer())
            <div>
                <label for="ruc" class="block text-sm font-medium text-slate-700 mb-1">RUC</label>
                <input type="text" name="ruc" id="ruc" value="{{ old('ruc', $user->ruc) }}" maxlength="20" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20" placeholder="RUC de tu empresa">
            </div>
            @endif
            </div>
        </div>

        <div class="pt-6 border-t border-slate-100">
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">Cambiar contraseña</h2>
            <p class="text-sm text-slate-500 mb-4">Deja los campos en blanco si no quieres cambiar la contraseña.</p>
            <div class="space-y-5">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-slate-700 mb-1">Contraseña actual</label>
                    <input type="password" name="current_password" id="current_password" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20" autocomplete="current-password">
                    @error('current_password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Nueva contraseña</label>
                    <input type="password" name="password" id="password" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20" autocomplete="new-password">
                    @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirmar nueva contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20" autocomplete="new-password">
                </div>
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-5 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700">Guardar cambios</button>
            <a href="{{ route('cuenta.dashboard') }}" class="px-5 py-2.5 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200">Cancelar</a>
        </div>
    </form>
</div>
@endsection
