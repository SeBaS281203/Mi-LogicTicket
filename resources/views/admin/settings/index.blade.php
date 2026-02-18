@extends('layouts.admin')

@section('title', 'Configuración')

@section('content')
<h1 class="text-3xl font-bold mb-6">Configuración global</h1>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-lg">
    <h2 class="text-lg font-semibold mb-4">Comisión por servicio</h2>
    <p class="text-slate-600 text-sm mb-4">Porcentaje aplicado sobre el subtotal de cada compra. Afecta a todas las ventas.</p>
    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
        @csrf
        <div>
            <label for="commission_percentage" class="block text-sm font-medium text-slate-700 mb-1">Porcentaje de comisión (%)</label>
            <input type="number" name="commission_percentage" id="commission_percentage" value="{{ old('commission_percentage', $commission) }}" min="0" max="100" step="0.01" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('commission_percentage')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Guardar</button>
    </form>
</div>
@endsection
