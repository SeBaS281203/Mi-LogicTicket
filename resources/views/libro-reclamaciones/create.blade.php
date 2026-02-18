@extends('layouts.app')

@section('title', 'Libro de Reclamaciones')
@section('meta_description', 'Registre su reclamo o queja en el Libro de Reclamaciones Virtual de LogicTicket. Normativa INDECOPI.')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-2xl shadow-lg border border-neutral-100 overflow-hidden">
        <div class="bg-gradient-to-br from-[#00a650] to-[#008f47] text-white px-6 sm:px-8 py-8">
            <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6">
                <div class="flex-shrink-0 flex items-center justify-center w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-white/20 backdrop-blur-sm border-2 border-white/30 shadow-lg">
                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="flex-shrink-0 flex items-center gap-3">
                    <span class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-white flex items-center justify-center text-[#00a650] text-xl sm:text-2xl font-extrabold shadow">L</span>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold leading-tight">Libro de Reclamaciones Virtual</h1>
                        <p class="text-white/90 text-sm mt-1">LogicTicket · Normativa INDECOPI (Perú)</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-6 sm:p-8">
            <p class="text-neutral-600 text-sm mb-6">Complete el formulario. Los campos marcados con * son obligatorios. Recibirá una constancia por correo y podrá descargarla en PDF.</p>

            <form method="POST" action="{{ route('libro-reclamaciones.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="tipo_documento" class="block text-sm font-semibold text-neutral-700 mb-1">Tipo de documento *</label>
                        <select name="tipo_documento" id="tipo_documento" required class="w-full px-4 py-3 rounded-xl border border-neutral-200 focus:border-[#00a650] focus:ring-2 focus:ring-[#00a650]/20">
                            <option value="">Seleccione</option>
                            @foreach(\App\Models\LibroReclamacion::TIPOS_DOCUMENTO as $t)
                                <option value="{{ $t }}" @if(old('tipo_documento') == $t) selected @endif>{{ $t }}</option>
                            @endforeach
                        </select>
                        @error('tipo_documento')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="numero_documento" class="block text-sm font-semibold text-neutral-700 mb-1">Número de documento *</label>
                        <input type="text" name="numero_documento" id="numero_documento" value="{{ old('numero_documento') }}" required maxlength="20" class="w-full px-4 py-3 rounded-xl border border-neutral-200 focus:border-[#00a650] focus:ring-2 focus:ring-[#00a650]/20" placeholder="Ej. 12345678">
                        @error('numero_documento')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="nombre_completo" class="block text-sm font-semibold text-neutral-700 mb-1">Nombre completo *</label>
                    <input type="text" name="nombre_completo" id="nombre_completo" value="{{ old('nombre_completo') }}" required maxlength="255" class="w-full px-4 py-3 rounded-xl border border-neutral-200 focus:border-[#00a650] focus:ring-2 focus:ring-[#00a650]/20">
                    @error('nombre_completo')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="direccion" class="block text-sm font-semibold text-neutral-700 mb-1">Dirección *</label>
                    <input type="text" name="direccion" id="direccion" value="{{ old('direccion') }}" required maxlength="500" class="w-full px-4 py-3 rounded-xl border border-neutral-200 focus:border-[#00a650] focus:ring-2 focus:ring-[#00a650]/20" placeholder="Distrito, calle, número">
                    @error('direccion')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="telefono" class="block text-sm font-semibold text-neutral-700 mb-1">Teléfono *</label>
                        <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}" required maxlength="30" class="w-full px-4 py-3 rounded-xl border border-neutral-200 focus:border-[#00a650] focus:ring-2 focus:ring-[#00a650]/20" placeholder="Ej. 999 888 777">
                        @error('telefono')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-semibold text-neutral-700 mb-1">Correo electrónico *</label>
                        <input type="email" name="email" id="email" value="{{ old('email', auth()->user()?->email) }}" required class="w-full px-4 py-3 rounded-xl border border-neutral-200 focus:border-[#00a650] focus:ring-2 focus:ring-[#00a650]/20">
                        @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="evento_id" class="block text-sm font-semibold text-neutral-700 mb-1">Servicio/Evento relacionado (opcional)</label>
                    <select name="evento_id" id="evento_id" class="w-full px-4 py-3 rounded-xl border border-neutral-200 focus:border-[#00a650] focus:ring-2 focus:ring-[#00a650]/20">
                        <option value="">Ninguno / Otro</option>
                        @foreach($eventos as $e)
                            <option value="{{ $e->id }}" @if((int)($eventoId ?? 0) === $e->id || (int)old('evento_id') === $e->id) selected @endif>{{ $e->title }} ({{ $e->start_date->format('d/m/Y') }})</option>
                        @endforeach
                    </select>
                    @error('evento_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-2">Tipo *</label>
                    <div class="flex gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="tipo_reclamo" value="reclamo" @checked(old('tipo_reclamo', 'reclamo') === 'reclamo') required>
                            <span>Reclamo</span> <span class="text-neutral-500 text-xs">(disconformidad con el servicio)</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="tipo_reclamo" value="queja" @checked(old('tipo_reclamo') === 'queja')>
                            <span>Queja</span> <span class="text-neutral-500 text-xs">(malestar no directo al servicio)</span>
                        </label>
                    </div>
                    @error('tipo_reclamo')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="descripcion" class="block text-sm font-semibold text-neutral-700 mb-1">Detalle del reclamo/queja *</label>
                    <textarea name="descripcion" id="descripcion" required rows="4" maxlength="5000" class="w-full px-4 py-3 rounded-xl border border-neutral-200 focus:border-[#00a650] focus:ring-2 focus:ring-[#00a650]/20" placeholder="Describa con claridad lo sucedido">{{ old('descripcion') }}</textarea>
                    @error('descripcion')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="pedido_consumidor" class="block text-sm font-semibold text-neutral-700 mb-1">Pedido del consumidor (opcional)</label>
                    <textarea name="pedido_consumidor" id="pedido_consumidor" rows="2" maxlength="2000" class="w-full px-4 py-3 rounded-xl border border-neutral-200 focus:border-[#00a650] focus:ring-2 focus:ring-[#00a650]/20" placeholder="Qué solución o respuesta espera">{{ old('pedido_consumidor') }}</textarea>
                    @error('pedido_consumidor')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <button type="submit" class="px-6 py-3 bg-[#00a650] hover:bg-[#009345] text-white font-semibold rounded-xl transition-colors">Enviar registro</button>
                    <a href="{{ route('home') }}" class="px-6 py-3 border border-neutral-200 rounded-xl font-medium text-neutral-600 hover:bg-neutral-50 text-center transition-colors">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
