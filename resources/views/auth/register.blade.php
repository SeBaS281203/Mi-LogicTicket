@extends('layouts.app')

@section('title', 'Registrarse')

@section('content')
<div class="max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-6">Crear cuenta</h1>
    <form method="POST" action="{{ route('register') }}" class="space-y-4 bg-white p-6 rounded-xl shadow-sm border border-slate-200">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nombre</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="role" class="block text-sm font-medium text-slate-700 mb-1">Tipo de cuenta *</label>
            <select name="role" id="role" required
                class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="client" {{ old('role', 'client') === 'client' ? 'selected' : '' }}>Cliente (comprar entradas)</option>
                <option value="organizer" {{ old('role') === 'organizer' ? 'selected' : '' }}>Organizador (crear y vender eventos)</option>
            </select>
            @error('role')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Teléfono (opcional)</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('phone')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Contraseña</label>
            <input type="password" name="password" id="password" required
                class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirmar contraseña</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required
                class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div>
            <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">Registrarse</button>
        </div>
    </form>
    <p class="mt-4 text-center text-slate-600">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-indigo-600 font-medium hover:underline">Inicia sesión</a></p>
</div>
@endsection
