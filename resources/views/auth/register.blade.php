@extends('layouts.app')

@section('title', 'Registrarse')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ role: @js(old('role', 'client')) }">
    <h1 class="text-2xl font-bold mb-6">Crear cuenta</h1>

    <form method="POST" action="{{ route('register') }}" class="space-y-5 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        @csrf

        <div>
            <p class="text-sm font-medium text-slate-700 mb-2">Tipo de cuenta</p>
            <div class="grid grid-cols-2 gap-2">
                <button type="button" @click="role = 'client'" :class="role === 'client' ? 'bg-violet-50 border-violet-500 text-violet-700' : 'bg-white border-slate-300 text-slate-600'" class="h-11 rounded-xl border text-sm font-semibold">Cliente</button>
                <button type="button" @click="role = 'organizer'" :class="role === 'organizer' ? 'bg-violet-50 border-violet-500 text-violet-700' : 'bg-white border-slate-300 text-slate-600'" class="h-11 rounded-xl border text-sm font-semibold">Organizador</button>
            </div>
            <input type="hidden" name="role" :value="role">
            @error('role')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="first_name" class="block text-sm font-medium text-slate-700 mb-1">Nombre</label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                @error('first_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium text-slate-700 mb-1">Apellidos</label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                @error('last_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="country" class="block text-sm font-medium text-slate-700 mb-1">País</label>
                <input type="text" name="country" id="country" value="{{ old('country', 'Perú') }}" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                @error('country')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="city" class="block text-sm font-medium text-slate-700 mb-1">Ciudad</label>
                <input type="text" name="city" id="city" value="{{ old('city') }}" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                @error('city')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="document_type" class="block text-sm font-medium text-slate-700 mb-1">Tipo de documento</label>
                <select name="document_type" id="document_type" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                    <option value="dni" {{ old('document_type', 'dni') === 'dni' ? 'selected' : '' }}>DNI</option>
                    <option value="ce" {{ old('document_type') === 'ce' ? 'selected' : '' }}>Carné de extranjería</option>
                    <option value="pasaporte" {{ old('document_type') === 'pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                </select>
                @error('document_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="document_number" class="block text-sm font-medium text-slate-700 mb-1">Nro. documento</label>
                <input type="text" name="document_number" id="document_number" value="{{ old('document_number') }}" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                @error('document_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="gender" class="block text-sm font-medium text-slate-700 mb-1">Género</label>
                <select name="gender" id="gender" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                    <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Hombre</option>
                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Mujer</option>
                    <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Otro</option>
                    <option value="prefer_not" {{ old('gender', 'prefer_not') === 'prefer_not' ? 'selected' : '' }}>Prefiero no decirlo</option>
                </select>
                @error('gender')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Teléfono</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div x-show="role === 'organizer'" x-cloak class="grid grid-cols-1 md:grid-cols-2 gap-4 border border-emerald-200 rounded-xl p-4 bg-emerald-50/40">
            <div>
                <label for="organization_name" class="block text-sm font-medium text-slate-700 mb-1">Nombre comercial</label>
                <input type="text" name="organization_name" id="organization_name" value="{{ old('organization_name') }}" :required="role === 'organizer'" class="w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                @error('organization_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="ruc" class="block text-sm font-medium text-slate-700 mb-1">RUC</label>
                <input type="text" name="ruc" id="ruc" value="{{ old('ruc') }}" maxlength="11" :required="role === 'organizer'" class="w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                @error('ruc')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
                <label for="organization_address" class="block text-sm font-medium text-slate-700 mb-1">Dirección fiscal</label>
                <input type="text" name="organization_address" id="organization_address" value="{{ old('organization_address') }}" :required="role === 'organizer'" class="w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                @error('organization_address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Correo electrónico</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">
            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Contraseña</label>
                <input type="password" name="password" id="password" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">
            </div>
        </div>

        <div class="space-y-2 text-sm">
            <label class="flex items-start gap-2 text-slate-600">
                <input type="checkbox" name="terms_accepted" value="1" {{ old('terms_accepted') ? 'checked' : '' }} required class="mt-1 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <span>Acepto los términos y condiciones.</span>
            </label>
            @error('terms_accepted')<p class="text-sm text-red-600">{{ $message }}</p>@enderror

            <label class="flex items-start gap-2 text-slate-600">
                <input type="checkbox" name="marketing_consent" value="1" {{ old('marketing_consent') ? 'checked' : '' }} class="mt-1 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <span>Acepto recibir promociones y beneficios.</span>
            </label>
        </div>

        <button type="submit" class="w-full py-3 px-4 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 font-semibold">Registrarse</button>
    </form>

    <p class="mt-4 text-center text-slate-600">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-violet-600 font-medium hover:underline">Inicia sesión</a></p>
</div>
@endsection
