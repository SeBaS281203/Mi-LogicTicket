@extends('layouts.app')

@section('title', 'Finalizar Compra')

@section('content')
@php
    $checkoutInitData = [
        'initialItems' => $items_for_js ?? [],
        'initialSubtotal' => $subtotal ?? 0,
        'initialCommission' => $commission_amount ?? 0,
        'initialTotal' => $total ?? 0,
        'commissionRate' => $commission_percentage ?? 0,
        'user' => Auth::user() ? ['name' => Auth::user()->name, 'email' => Auth::user()->email] : null,
        'stripeEnabled' => $stripe_enabled ?? false,
        'stripeKey' => $stripe_key ?? '',
    ];
@endphp
<div class="min-h-screen bg-slate-50 py-10 lg:py-20" x-data="checkoutProcess(@js($checkoutInitData))">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Progress Indicator (Carrito / Datos / ConfirmaciÃƒÂ³n / Pago) --}}
        <x-checkout-steps :current="'step'" />

        <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-start mt-8">
            
            {{-- Form Column --}}
            <div class="lg:col-span-8 space-y-8">
                
                {{-- STEP 2: DATOS DEL COMPRADOR --}}
                <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="bg-white rounded-3xl shadow-lg border border-slate-100 overflow-hidden">
                        <div class="p-6 sm:p-8 lg:p-10">
                            <h2 class="text-2xl font-black text-slate-900 mb-8 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-violet-100 text-[#7c3aed] flex items-center justify-center text-sm">2</span>
                                Datos del comprador
                            </h2>

                            <div class="space-y-6">
                                <div class="grid sm:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-black uppercase text-slate-400 tracking-widest mb-2">Nombre completo</label>
                                        <input type="text" x-model="formData.name" required placeholder="Escribe tu nombre"
                                            class="w-full px-6 py-4 rounded-xl border border-slate-200 focus:border-[#7c3aed] focus:ring-0 text-slate-900 font-bold transition-all placeholder:text-slate-300">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black uppercase text-slate-400 tracking-widest mb-2">Correo electrÃƒÂ³nico</label>
                                        <input type="email" x-model="formData.email" required placeholder="tu@email.com"
                                            class="w-full px-6 py-4 rounded-xl border border-slate-200 focus:border-[#7c3aed] focus:ring-0 text-slate-900 font-bold transition-all placeholder:text-slate-300">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-black uppercase text-slate-400 tracking-widest mb-2">TelÃƒÂ©fono (WhatsApp)</label>
                                    <input type="tel" x-model="formData.phone" placeholder="999 999 999"
                                        class="w-full px-6 py-4 rounded-xl border border-slate-200 focus:border-[#7c3aed] focus:ring-0 text-slate-900 font-bold transition-all placeholder:text-slate-300">
                                </div>

                                <label class="flex items-center gap-3 cursor-pointer group pt-4">
                                    <input type="checkbox" x-model="formData.accept_terms" required class="w-6 h-6 rounded-lg text-[#7c3aed] border-slate-300 focus:ring-0 cursor-pointer transition-all">
                                    <span class="text-sm text-slate-500 font-bold group-hover:text-slate-700 transition-colors">Acepto los <a href="#" class="text-[#7c3aed] underline">tÃƒÂ©rminos y condiciones</a> de ChiclayoTicket.</span>
                                </label>

                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 flex items-center gap-3 mt-8">
                                    <i class="fas fa-lock text-[#7c3aed]"></i>
                                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Compra 100% segura con {{ $payment_provider_name ?? 'pago seguro' }}</p>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-4 mt-10">
                                    <a href="{{ route('cart.index') }}" class="flex-1 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black rounded-2xl transition-all text-center flex items-center justify-center text-sm">
                                        VOLVER AL CARRITO
                                    </a>
                                    <button @click="if(validateStep2()) step = 3" class="flex-[2] py-4 bg-slate-900 hover:bg-black text-white font-black rounded-2xl shadow-lg transition-all flex items-center justify-center gap-3 text-sm">
                                        REVISAR PEDIDO
                                        <i class="fas fa-arrow-right text-[#7c3aed]"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STEP 3: CONFIRMACIÃƒâ€œN --}}
                <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
                    <div class="bg-white rounded-3xl shadow-lg border border-slate-100 overflow-hidden">
                        <div class="p-6 sm:p-8 lg:p-10">
                            <h2 class="text-2xl font-black text-slate-900 mb-8 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-violet-100 text-[#7c3aed] flex items-center justify-center text-sm">3</span>
                                ConfirmaciÃƒÂ³n de tu pedido
                            </h2>

                            <div class="bg-slate-50 rounded-2xl p-6 mb-8 border border-slate-100">
                                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Datos del Asistente</h3>
                                <div class="grid sm:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase">Nombre</p>
                                        <p class="text-sm font-black text-slate-900" x-text="formData.name"></p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase">Correo</p>
                                        <p class="text-sm font-black text-slate-900" x-text="formData.email"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <template x-for="(item, index) in items" :key="item.id">
                                    <div class="flex items-center justify-between p-4 rounded-xl bg-white border border-slate-100">
                                        <div class="flex gap-4">
                                            <div class="w-12 h-12 rounded-lg bg-violet-600 flex items-center justify-center text-white shrink-0">
                                                <i class="fas fa-ticket-alt"></i>
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-900 leading-tight" x-text="item.event_title"></p>
                                                <p class="text-[10px] font-black text-slate-400 uppercase" x-text="item.ticket_type_name + ' Ãƒâ€” ' + item.quantity"></p>
                                            </div>
                                        </div>
                                        <p class="font-black text-slate-900" x-text="'S/ ' + (item.price * item.quantity).toFixed(2)"></p>
                                    </div>
                                </template>
                            </div>

                            <div class="mt-10 pt-8 border-t border-slate-100 space-y-3 text-right">
                                <div class="flex justify-between text-slate-500 text-sm font-medium">
                                    <span>Subtotal</span>
                                    <span x-text="'S/ ' + subtotal.toFixed(2)"></span>
                                </div>
                                <div class="flex justify-between text-slate-500 text-sm font-medium">
                                    <span x-text="'ComisiÃƒÂ³n de servicio (' + commissionRate + '%)'"></span>
                                    <span x-text="'S/ ' + (subtotal * (commissionRate / 100)).toFixed(2)"></span>
                                </div>
                                <div class="flex justify-between items-center pt-4 border-t border-slate-200">
                                    <span class="text-xl font-black text-slate-900">Total Final</span>
                                    <span class="text-3xl font-black text-[#7c3aed]" x-text="'S/ ' + total.toFixed(2)"></span>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-4 mt-10">
                                <button type="button" @click="step = 2" class="flex-1 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black rounded-2xl transition-all text-sm">
                                    EDITAR DATOS
                                </button>
                                <button @click="step = 4" class="flex-[2] py-4 bg-slate-900 hover:bg-black text-white font-black rounded-2xl shadow-lg transition-all flex items-center justify-center gap-3 text-sm">
                                    IR AL PAGO
                                    <i class="fas fa-arrow-right text-[#7c3aed]"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STEP 4: PAGO (Selecciona cÃ³mo pagar) --}}
                <div x-show="step === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="bg-white rounded-3xl shadow-lg border border-slate-100 overflow-hidden">
                        <div class="p-6 sm:p-8 lg:p-10">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm">4</span>
                                        Selecciona cÃƒÂ³mo pagar
                                    </h2>
                                    <p class="text-slate-500 text-sm mt-1">Elige el mÃƒÂ©todo de pago para tu compra.</p>
                                </div>
                                <div class="hidden sm:flex items-center gap-2 text-xs font-semibold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-100">
                                    <i class="fas fa-clock"></i>
                                    <span>Tienes tiempo de sobra</span>
                                </div>
                            </div>

                            <label class="flex items-start gap-3 p-4 border border-slate-200 rounded-2xl mb-6 cursor-pointer hover:border-emerald-300 transition-colors">
                                <input type="checkbox" class="mt-1 w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-0">
                                <span class="text-xs sm:text-sm text-slate-600">
                                    Doy mi consentimiento para usos adicionales y disfrutar de los beneficios, promociones y descuentos creados para mÃƒÂ­.
                                </span>
                            </label>

                            <div class="space-y-4">
                                {{-- JoinnusPay via BCP (recomendado) --}}
                                <div class="relative border rounded-2xl overflow-hidden bg-white cursor-pointer"
                                     :class="paymentMethod === 'joinnusp_bcp' ? 'border-emerald-500 shadow-[0_0_0_2px_rgba(16,185,129,0.12)]' : 'border-slate-200 hover:border-emerald-300'">
                                    <div class="absolute -top-1 left-4 px-3 py-0.5 rounded-b-md bg-amber-300 text-slate-900 text-[11px] font-bold">
                                        Recomendado
                                    </div>
                                    <button type="button"
                                        @click="paymentMethod = 'joinnusp_bcp'"
                                        class="w-full flex items-center justify-between px-4 sm:px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-5 h-5 rounded border flex items-center justify-center"
                                                 :class="paymentMethod === 'joinnusp_bcp' ? 'border-emerald-500 bg-emerald-500' : 'border-slate-300'">
                                                <div class="w-2.5 h-2.5 rounded-sm bg-white" x-show="paymentMethod === 'joinnusp_bcp'"></div>
                                            </div>
                                            <span class="text-sm sm:text-base font-semibold text-slate-900">JoinnusPay via BCP</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="px-3 py-1 rounded-full bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-600">+ S/ 3.90</span>
                                            <div class="h-9 min-w-[120px] px-3 rounded-md bg-slate-900 text-white flex items-center justify-between gap-2">
                                                <span class="text-[10px] font-semibold tracking-wide uppercase">JoinnusPay</span>
                                                <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Logo-bcp-vector.svg"
                                                     alt="BCP"
                                                     class="h-5 w-auto object-contain"
                                                     loading="lazy">
                                            </div>
                                        </div>
                                    </button>
                                </div>

                                {{-- Yape --}}
                                <button type="button"
                                    @click="paymentMethod = 'yape'"
                                    class="w-full flex items-center justify-between px-4 sm:px-6 py-4 rounded-2xl border bg-white cursor-pointer"
                                    :class="paymentMethod === 'yape' ? 'border-emerald-500 shadow-[0_0_0_2px_rgba(16,185,129,0.12)]' : 'border-slate-200 hover:border-emerald-300'">
                                    <div class="flex items-center gap-3">
                                        <div class="w-5 h-5 rounded border flex items-center justify-center"
                                             :class="paymentMethod === 'yape' ? 'border-emerald-500 bg-emerald-500' : 'border-slate-300'">
                                            <div class="w-2.5 h-2.5 rounded-sm bg-white" x-show="paymentMethod === 'yape'"></div>
                                        </div>
                                        <span class="text-sm sm:text-base font-semibold text-slate-900">Yape</span>
                                    </div>
                                    <div class="h-9 min-w-[120px] px-3 rounded-md bg-[#702283] flex items-center justify-center">
                                        <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Yape_peru_logotype.svg"
                                             alt="Yape"
                                             class="h-5 w-auto object-contain"
                                             loading="lazy">
                                    </div>
                                </button>

                                {{-- Tarjetas de credito / debito --}}
                                <button type="button"
                                    @click="paymentMethod = 'card_visa'"
                                    class="w-full flex items-center justify-between px-4 sm:px-6 py-4 rounded-2xl border bg-white cursor-pointer"
                                    :class="paymentMethod === 'card_visa' ? 'border-emerald-500 shadow-[0_0_0_2px_rgba(16,185,129,0.12)]' : 'border-slate-200 hover:border-emerald-300'">
                                    <div class="flex items-center gap-3">
                                        <div class="w-5 h-5 rounded border flex items-center justify-center"
                                             :class="paymentMethod === 'card_visa' ? 'border-emerald-500 bg-emerald-500' : 'border-slate-300'">
                                            <div class="w-2.5 h-2.5 rounded-sm bg-white" x-show="paymentMethod === 'card_visa'"></div>
                                        </div>
                                        <span class="text-sm sm:text-base font-semibold text-slate-900">Tarjetas de credito / debito</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <img src="https://cdn.simpleicons.org/visa/1A1F71" alt="Visa" class="h-6 w-10 object-contain" loading="lazy">
                                        <img src="https://cdn.simpleicons.org/mastercard/EB001B" alt="Mastercard" class="h-6 w-10 object-contain" loading="lazy">
                                        <img src="https://cdn.simpleicons.org/americanexpress/2E77BC" alt="American Express" class="h-6 w-10 object-contain" loading="lazy">
                                    </div>
                                </button>

                                {{-- Millas Benefit --}}
                                <button type="button"
                                    @click="paymentMethod = 'benefit'"
                                    class="w-full flex items-center justify-between px-4 sm:px-6 py-4 rounded-2xl border bg-white cursor-pointer"
                                    :class="paymentMethod === 'benefit' ? 'border-emerald-500 shadow-[0_0_0_2px_rgba(16,185,129,0.12)]' : 'border-slate-200 hover:border-emerald-300'">
                                    <div class="flex items-center gap-3">
                                        <div class="w-5 h-5 rounded border flex items-center justify-center"
                                             :class="paymentMethod === 'benefit' ? 'border-emerald-500 bg-emerald-500' : 'border-slate-300'">
                                            <div class="w-2.5 h-2.5 rounded-sm bg-white" x-show="paymentMethod === 'benefit'"></div>
                                        </div>
                                        <span class="text-sm sm:text-base font-semibold text-slate-900">Millas Benefit</span>
                                    </div>
                                    <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Interbank_logo.svg"
                                         alt="Interbank Benefit"
                                         class="h-8 w-auto max-w-[150px] object-contain"
                                         loading="lazy">
                                </button>

                                {{-- Pago en efectivo --}}
                                <button type="button"
                                    @click="paymentMethod = 'cash'"
                                    class="w-full flex items-center justify-between px-4 sm:px-6 py-4 rounded-2xl border bg-white cursor-pointer"
                                    :class="paymentMethod === 'cash' ? 'border-emerald-500 shadow-[0_0_0_2px_rgba(16,185,129,0.12)]' : 'border-slate-200 hover:border-emerald-300'">
                                    <div class="flex items-center gap-3">
                                        <div class="w-5 h-5 rounded border flex items-center justify-center"
                                             :class="paymentMethod === 'cash' ? 'border-emerald-500 bg-emerald-500' : 'border-slate-300'">
                                            <div class="w-2.5 h-2.5 rounded-sm bg-white" x-show="paymentMethod === 'cash'"></div>
                                        </div>
                                        <span class="text-sm sm:text-base font-semibold text-slate-900">Pago en efectivo</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="px-3 py-1 rounded-full bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-600">+ S/ 3.90</span>
                                        <img src="https://static.openfintech.io/payment_methods/pagoefectivo/logo.png?w=400&c=v0.59.26"
                                             alt="PagoEfectivo"
                                             class="h-7 w-auto max-w-[140px] object-contain"
                                             loading="lazy">
                                    </div>
                                </button>
                            </div>

                            {{-- Pago con tarjeta por redireccion a Stripe Checkout --}}
                            <div x-show="stripeEnabled && paymentMethod === 'card_visa'" x-cloak x-transition
                                 class="mt-6 p-6 rounded-2xl border border-slate-200 bg-slate-50">
                                <p class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-3">Pago con tarjeta</p>
                                <p class="text-sm text-slate-600">
                                    Al continuar, serÃ¡s redirigido a <strong>Stripe Checkout</strong> para completar tu pago de forma segura.
                                </p>
                                <p class="mt-2 text-xs text-slate-500">No ingreses tu tarjeta aquÃ­; el formulario se abrirÃ¡ en Stripe.</p>
                            </div>

                            <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-100 pt-4">
                                <div class="w-full sm:w-auto flex items-center justify-between sm:justify-start gap-4 text-sm">
                                    <span class="text-slate-500">Total a pagar</span>
                                    <span class="text-xl font-black text-violet-700" x-text="'S/ ' + total.toFixed(2)"></span>
                                </div>
                                <div class="w-full sm:w-auto flex flex-col sm:flex-row gap-3">
                                    <button @click="step = 3" :disabled="loading" class="w-full sm:w-auto px-5 py-3 rounded-2xl border border-slate-200 text-slate-600 text-xs font-semibold uppercase tracking-widest hover:bg-slate-50 transition-colors">
                                        Volver
                                    </button>
                                    <button @click="processFinalPayment()" :disabled="loading" class="w-full sm:w-auto px-8 py-3 rounded-2xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-semibold uppercase tracking-widest flex items-center justify-center gap-2 shadow-sm transition-colors">
                                        <span x-text="loading ? 'Procesando...' : (stripeEnabled && paymentMethod === 'card_visa' ? 'Pagar con tarjeta' : 'Siguiente')"></span>
                                        <i class="fas fa-arrow-right" x-show="!loading"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Column (Desktop Only) --}}
            <div class="lg:col-span-4 hidden lg:block">
                <div class="sticky top-24 space-y-6">
                    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                        <div class="p-8">
                            <h3 class="text-lg font-black text-slate-900 mb-6 uppercase tracking-tight">Tu Compra</h3>
                            
                            <div class="space-y-4 mb-8">
                                <template x-for="item in items">
                                    <div class="flex justify-between items-start gap-3">
                                        <div class="text-sm">
                                            <p class="font-black text-slate-800 leading-tight" x-text="item.event_title"></p>
                                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-wider mt-1" x-text="item.quantity + 'x ' + item.ticket_type_name"></p>
                                        </div>
                                        <p class="text-sm font-black text-slate-900" x-text="'S/ ' + ((item.price * item.quantity) || 0).toFixed(2)"></p>
                                    </div>
                                </template>
                            </div>

                            <div class="border-t border-slate-100 py-6 space-y-2">
                                <div class="flex justify-between text-slate-500 text-sm font-medium">
                                    <span>Subtotal</span>
                                    <span x-text="'S/ ' + (subtotal || 0).toFixed(2)">S/ {{ number_format($subtotal ?? 0, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-slate-500 text-sm font-medium">
                                    <span>ComisiÃ³n de servicio</span>
                                    <span x-text="'S/ ' + ((subtotal || 0) * ((commissionRate || 0) / 100)).toFixed(2)">S/ {{ number_format($commission_amount ?? 0, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-4 border-t border-slate-200">
                                    <span class="text-slate-900 font-black tracking-tight">Total</span>
                                    <span class="text-2xl font-black text-[#7c3aed]" x-text="'S/ ' + (total || 0).toFixed(2)">S/ {{ number_format($total ?? 0, 2) }}</span>
                                </div>
                            </div>

                            <div class="bg-slate-50 rounded-2xl p-6 space-y-3">
                                <div class="flex items-center gap-3 text-slate-400">
                                    <i class="fas fa-check-circle text-[#7c3aed]"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Entradas 100% autÃƒÂ©nticas</span>
                                </div>
                                <div class="flex items-center gap-3 text-slate-400">
                                    <i class="fas fa-lock text-[#7c3aed]"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Pago seguro {{ $payment_provider_name ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@if($stripe_enabled ?? false)
<script src="https://js.stripe.com/v3/"></script>
@endif
<script>
function checkoutProcess(data) {
    const rawItems = Array.isArray(data.initialItems) ? data.initialItems : [];
    const items = rawItems.map(i => {
        const tt = i.ticket_type || {};
        const ev = tt.event || {};
        return {
            id: tt.id || 0,
            event_title: ev.title || 'Evento',
            ticket_type_name: tt.name || 'Entrada',
            price: parseFloat(i.unit_price || tt.price || 0) || 0,
            quantity: parseInt(i.quantity, 10) || 0
        };
    });
    return {
        step: 2,
        items: items,
        commissionRate: parseFloat(data.commissionRate) || 0,
        loading: false,
        paymentMethod: 'card_visa',
        formData: {
            name: data.user ? data.user.name : '',
            email: data.user ? data.user.email : '',
            phone: '',
            accept_terms: false
        },
        stripeEnabled: data.stripeEnabled || false,
        stripeKey: data.stripeKey || '',
        cardError: '',
        stripeInstance: null,
        cardElement: null,
        cardElementMounted: false,

        get paymentMethodLabel() {
            switch (this.paymentMethod) {
                case 'yape': return 'Yape';
                case 'card_visa': return 'Tarjeta de crÃ©dito / dÃ©bito';
                case 'card_amex': return 'Tarjeta de crÃ©dito / dÃ©bito';
                case 'benefit': return 'Millas Benefit';
                case 'cash': return 'Pago en efectivo';
                default: return 'JoinnusPay vÃ­a BCP';
            }
        },

        get subtotal() {
            const s = this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            return Number.isFinite(s) ? s : 0;
        },

        get total() {
            const sub = this.subtotal;
            const rate = Number.isFinite(this.commissionRate) ? this.commissionRate : 0;
            const t = sub + (sub * (rate / 100));
            return Number.isFinite(t) ? t : sub;
        },

        validateStep2() {
            if (!this.formData.name || !this.formData.email || !this.formData.accept_terms) {
                alert('Por favor completa todos los campos requeridos y acepta los tÃ©rminos.');
                return false;
            }
            return true;
        },

        initStripeCard() {
            if (!this.stripeKey || this.cardElementMounted || !window.Stripe) return;
            const el = document.getElementById('card-element');
            if (!el || el.children.length > 0) return;
            this.stripeInstance = window.Stripe(this.stripeKey);
            const elements = this.stripeInstance.elements();
            this.cardElement = elements.create('card', {
                style: { base: { fontSize: '16px', color: '#1e293b' } }
            });
            this.cardElement.mount('#card-element');
            this.cardElement.on('change', (e) => { this.cardError = e.error ? e.error.message : ''; });
            this.cardElementMounted = true;
        },

        async processFinalPayment() {
            if (this.loading) return;
            this.cardError = '';

            this.loading = true;
            try {
                const orderRes = await fetch('{{ route("checkout.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        customer_name: this.formData.name,
                        customer_email: this.formData.email,
                        customer_phone: this.formData.phone,
                        accept_terms: this.formData.accept_terms,
                        payment_method: this.paymentMethod
                    })
                });
                const orderData = await orderRes.json();
                if (!orderRes.ok) throw new Error(orderData.message || orderData.errors?.accept_terms?.[0] || 'Error al procesar la orden');
                if (orderData.redirect) {
                    window.location.href = orderData.redirect;
                    return;
                }
                const prefRes = await fetch('/api/payments/create-preference', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ order_id: orderData.order_id })
                });
                const prefData = await prefRes.json();
                if (!prefRes.ok) throw new Error(prefData.error || 'Error con Mercado Pago');
                window.location.href = prefData.sandbox_init_point || prefData.init_point;
            } catch (err) {
                this.loading = false;
                alert(err.message || 'OcurriÃ³ un error inesperado');
            }
        },

        async payWithStripeCard() {
            if (!this.stripeKey || !window.Stripe) {
                alert('Pago con tarjeta no disponible.');
                return;
            }
            this.loading = true;
            try {
                const res = await fetch('{{ route("checkout.create-payment-intent") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        customer_name: this.formData.name,
                        customer_email: this.formData.email,
                        customer_phone: this.formData.phone,
                        accept_terms: this.formData.accept_terms
                    })
                });
                const data = await res.json();
                if (data.redirect) {
                    window.location.href = data.redirect;
                    return;
                }
                if (!res.ok) throw new Error(data.message || 'Error al crear el pago');
                const clientSecret = data.client_secret;
                if (!clientSecret) throw new Error('No se recibiÃ³ la informaciÃ³n de pago');

                this.initStripeCard();
                if (!this.cardElement) throw new Error('No se pudo cargar el formulario de tarjeta');

                const { error, paymentIntent } = await this.stripeInstance.confirmCardPayment(clientSecret, {
                    payment_method: { card: this.cardElement }
                });
                if (error) {
                    this.cardError = error.message || 'Error en el pago';
                    this.loading = false;
                    return;
                }
                window.location.href = '{{ url("stripe/success") }}?payment_intent=' + paymentIntent.id;
            } catch (err) {
                this.loading = false;
                this.cardError = err.message || 'Error inesperado';
                alert(err.message || 'OcurriÃ³ un error. Intenta de nuevo.');
            }
        }
    };
}
</script>
@endpush
@endsection
