@extends('layouts.app')

@section('title', 'Resumen de Compra')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 mb-20 lg:mb-0" x-data="cartApp({
    initialItems: @js(collect($items)->map(fn($i) => [
        'id' => $i->ticket_type->id,
        'name' => $i->ticket_type->name,
        'price' => (float) $i->ticket_type->price,
        'quantity' => (int) $i->quantity,
        'stock' => (int) $i->ticket_type->available_quantity,
        'subtotal' => (float) $i->subtotal,
        'event_id' => $i->ticket_type->event->id,
        'event_title' => $i->ticket_type->event->title,
        'event_date' => $i->ticket_type->event->start_date->translatedFormat('d M Y') . ' · ' . $i->ticket_type->event->start_date->format('H:i'),
        'event_location' => $i->ticket_type->event->venue_name . ', ' . $i->ticket_type->event->city,
        'event_image' => $i->ticket_type->event->event_image ?? $i->ticket_type->event->image ?? null,
        'loading' => false,
        'confirmingDelete' => false,
        'removing' => false
    ])),
    commissionRate: @js($commission_percentage / 100)
})" x-init="init()">
    {{-- Progress Steps --}}
    <div class="hidden sm:block">
        <x-checkout-steps :current="1" />
    </div>

    <div x-show="items.length > 0" x-cloak>
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8 sm:mb-12">
            <div>
                <h1 class="text-3xl sm:text-4xl font-black text-slate-900 mb-2">Tu Resumen</h1>
                <p class="text-slate-500 font-medium text-sm sm:text-base">
                    Tienes <span x-text="items.length" class="font-semibold text-slate-900"></span> tipos de entrada listos.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12 items-start">
            {{-- Items List --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-lg border border-slate-100 p-6 sm:p-8 space-y-4">
                    <template x-for="item in items" :key="item.id">
                        <div class="flex items-center gap-4 sm:gap-6 border border-slate-100 rounded-2xl px-4 sm:px-6 py-4 sm:py-5 bg-white">
                            <div class="w-24 h-20 sm:w-32 sm:h-24 rounded-2xl overflow-hidden bg-slate-100 flex-shrink-0">
                                <img :src="item.event_image ? (item.event_image.startsWith('http') ? item.event_image : '/storage/' + item.event_image) : 'https://picsum.photos/seed/event-' + item.event_id + '/400/400'"
                                     class="w-full h-full object-cover">
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-sm sm:text-base font-bold text-slate-900 truncate" x-text="item.event_title"></p>
                                <p class="text-[11px] text-slate-400 font-medium mt-1 truncate">
                                    <span x-text="item.event_date"></span> · <span x-text="item.event_location"></span>
                                </p>
                                <p class="text-[13px] mt-2">
                                    <span class="font-semibold text-slate-700" x-text="item.name"></span>
                                    <span class="text-violet-600 font-semibold" x-text="' · S/ ' + item.price.toFixed(2) + ' c/u'"></span>
                                </p>

                                <div class="mt-3 flex items-center gap-3">
                                    <div class="flex items-center rounded-full bg-slate-50 border border-slate-200">
                                        <button @click="decrement(item)"
                                                :disabled="item.quantity <= 1 || item.loading"
                                                class="w-8 h-8 flex items-center justify-center text-slate-500 disabled:opacity-40">
                                            −
                                        </button>
                                        <span class="w-8 text-center text-sm font-semibold text-slate-900" x-text="item.quantity"></span>
                                        <button @click="increment(item)"
                                                :disabled="item.quantity >= item.stock || item.loading"
                                                class="w-8 h-8 flex items-center justify-center text-slate-500 disabled:opacity-40">
                                            +
                                        </button>
                                    </div>

                                    <button @click="confirmRemove(item)"
                                            :disabled="item.loading"
                                            class="text-[12px] font-semibold text-red-500 hover:text-red-600">
                                        Eliminar
                                    </button>

                                    <div class="ml-auto text-sm sm:text-base font-bold text-slate-900 whitespace-nowrap">
                                        S/ <span x-text="(item.price * item.quantity).toFixed(2)"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Summary Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-lg border border-slate-100 p-6 sm:p-8">
                    <h2 class="text-lg font-black text-slate-900 mb-6 tracking-tight uppercase">Resumen Total</h2>

                    <div class="space-y-2 text-sm mb-6">
                        <div class="flex justify-between text-slate-500">
                            <span>Subtotal</span>
                            <span x-text="'S/ ' + subtotal.toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span x-text="'Comisión (' + (commissionRate * 100).toFixed(0) + '%)'"></span>
                            <span x-text="'S/ ' + commissionAmount.toFixed(2)"></span>
                        </div>
                        <div class="pt-3 mt-1 border-t border-slate-100 flex justify-between items-center">
                            <span class="text-sm font-semibold text-slate-900">Total Final</span>
                            <span class="text-2xl font-black text-violet-600" x-text="'S/ ' + total.toFixed(2)"></span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}"
                       :class="anyLoading ? 'opacity-50 pointer-events-none' : ''"
                       class="block w-full py-4 bg-slate-900 hover:bg-black text-white font-black rounded-2xl text-center text-sm sm:text-base transition-colors">
                        CONTINUAR
                    </a>

                    <div class="mt-6 space-y-2 text-[11px] text-slate-400">
                        <p class="uppercase tracking-widest">Seguridad de pago MP</p>
                        <p class="uppercase tracking-widest">Soporte express 24h</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Empty State --}}
    <div x-show="items.length === 0" x-cloak class="text-center py-20 px-6 bg-white rounded-3xl border border-slate-100 shadow-lg">
        <div class="w-28 h-28 sm:w-36 sm:h-36 bg-violet-50 rounded-[2.5rem] sm:rounded-[3.5rem] flex items-center justify-center mx-auto mb-10 transform -rotate-6">
            <i class="fas fa-ticket-alt text-4xl sm:text-5xl text-violet-600 rotate-12"></i>
        </div>
        <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mb-4">¿No ves nada por aquí?</h2>
        <p class="text-slate-400 font-bold mb-14 max-w-sm mx-auto text-lg sm:text-xl leading-relaxed">Tu carrito está actualmente vacío. ¡Vuelve a los eventos y encuentra tu próxima experiencia!</p>
        <a href="{{ route('events.index') }}" class="px-10 py-5 sm:px-12 sm:py-6 bg-slate-900 hover:bg-black text-white font-black rounded-3xl shadow-2xl transition-all inline-flex items-center gap-5 transform hover:-translate-y-1">
            BUSCAR EVENTOS
            <i class="fas fa-search text-violet-600"></i>
        </a>
    </div>
</div>

@push('scripts')
<script>
function cartApp(data) {
    return {
        items: data.initialItems,
        commissionRate: data.commissionRate,
        toasts: [],
        
        init() {
            // Watch for item changes and update global store
            this.$watch('items', (value) => {
                const totalQty = value.reduce((sum, i) => sum + i.quantity, 0);
                if (window.Alpine && window.Alpine.store('cart')) {
                    window.Alpine.store('cart').updateCount(totalQty);
                }
            });
            
            // Initial sync
            const initialQty = this.items.reduce((sum, i) => sum + i.quantity, 0);
            if (window.Alpine && window.Alpine.store('cart')) {
                window.Alpine.store('cart').updateCount(initialQty);
            }
        },

        get subtotal() {
            return this.items.reduce((sum, i) => sum + (i.price * i.quantity), 0);
        },
        
        get commissionAmount() {
            return this.subtotal * this.commissionRate;
        },
        
        get total() {
            return this.subtotal + this.commissionAmount;
        },
        
        get anyLoading() {
            return this.items.some(i => i.loading);
        },
        
        showToast(message, type = 'success') {
            const id = Date.now();
            this.toasts.push({ id, message, type, show: true });
            setTimeout(() => {
                const toast = this.toasts.find(t => t.id === id);
                if (toast) toast.show = false;
            }, 3000);
        },

        increment(item) {
            if (item.quantity < item.stock) {
                item.quantity++;
                this.updateQuantity(item);
            }
        },

        decrement(item) {
            if (item.quantity > 1) {
                item.quantity--;
                this.updateQuantity(item);
            }
        },

        confirmRemove(item) {
            this.items.forEach(i => i.confirmingDelete = false);
            item.confirmingDelete = true;
        },
        
        async updateQuantity(item) {
            item.loading = true;
            try {
                const res = await fetch(`/cart/${item.id}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        quantity: item.quantity
                    })
                });
                
                const data = await res.json();
                if (data.success) {
                    item.quantity = data.item.quantity;
                    item.subtotal = data.item.subtotal;
                    this.showToast('Resumen actualizado');
                } else {
                    item.quantity = data.current_quantity || item.quantity;
                    this.showToast(data.message || 'Stock insuficiente', 'error');
                }
            } catch (e) {
                this.showToast('Error de conexión', 'error');
            } finally {
                item.loading = false;
            }
        },
        
        async removeItem(item) {
            item.loading = true;
            item.confirmingDelete = false;
            try {
                const res = await fetch(`/cart/${item.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await res.json();
                if (data.success) {
                    item.removing = true;
                    setTimeout(() => {
                        this.items = this.items.filter(i => i.id !== item.id);
                        this.showToast('Eliminado de tu resumen');
                    }, 300);
                } else {
                    this.showToast('Error al eliminar', 'error');
                    item.loading = false;
                }
            } catch (e) {
                this.showToast('Error de conexión', 'error');
                item.loading = false;
            }
        }
    };
}
</script>
@endpush
@endsection
