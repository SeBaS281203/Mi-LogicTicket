@props([])

<div
    x-data="{}"
    @keydown.escape.window="$store.toast.items = []"
    class="fixed top-4 right-4 z-[200] flex flex-col gap-3 w-full max-w-sm sm:max-w-md pointer-events-none"
    aria-live="polite"
    aria-label="Notificaciones"
>
    <template x-for="toast in $store.toast.items" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-12"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-8"
            class="pointer-events-auto flex items-start gap-3 rounded-xl shadow-lg border p-4 backdrop-blur-sm animate-toast-in"
            :class="{
                'bg-violet-50/95 border-violet-200 text-violet-900': toast.type === 'success',
                'bg-red-50/95 border-red-200 text-red-900': toast.type === 'error',
                'bg-amber-50/95 border-amber-200 text-amber-900': toast.type === 'warning',
                'bg-sky-50/95 border-sky-200 text-sky-900': toast.type === 'info'
            }"
        >
            <span
                class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center"
                :class="{
                    'bg-violet-500/20 text-violet-600': toast.type === 'success',
                    'bg-red-500/20 text-red-600': toast.type === 'error',
                    'bg-amber-500/20 text-amber-600': toast.type === 'warning',
                    'bg-sky-500/20 text-sky-600': toast.type === 'info'
                }"
            >
                <template x-if="toast.type === 'success'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </template>
                <template x-if="toast.type === 'error'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </template>
                <template x-if="toast.type === 'warning'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </template>
                <template x-if="toast.type === 'info'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </template>
            </span>
            <p class="flex-1 text-sm font-medium leading-snug" x-text="toast.message"></p>
            <button
                type="button"
                @click="$store.toast.remove(toast.id)"
                class="flex-shrink-0 p-1 rounded-lg hover:bg-black/5 transition-colors -m-1"
                aria-label="Cerrar"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </template>
</div>
