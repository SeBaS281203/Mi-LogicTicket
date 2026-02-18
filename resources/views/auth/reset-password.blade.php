@extends('layouts.app')

@section('title', 'Nueva contraseña')

@section('content')
<div class="max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-6">Nueva contraseña</h1>
    <form method="POST" action="{{ route('password.update') }}" class="space-y-4 bg-white p-6 rounded-xl shadow-sm border border-slate-200">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ $email }}" required
                class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('email')
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
            <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">Restablecer contraseña</button>
        </div>
    </form>
</div>
@endsection
