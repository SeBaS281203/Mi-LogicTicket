@extends('layouts.app')

@section('title', 'Recuperar contraseña')

@section('content')
<div class="max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-6">Recuperar contraseña</h1>
    @if(session('status'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">{{ session('status') }}</div>
    @endif
    <form method="POST" action="{{ route('password.email') }}" class="space-y-4 bg-white p-6 rounded-xl shadow-sm border border-slate-200">
        @csrf
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">Enviar enlace</button>
        </div>
    </form>
    <p class="mt-4 text-center"><a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Volver al login</a></p>
</div>
@endsection
