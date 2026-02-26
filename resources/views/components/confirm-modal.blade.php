@props([])

<div
    x-data="{}"
    x-show="$store.confirm.open"
    x-cloak
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @keydown.escape.window="$store.confirm.cancel()"
    class="fixed inset-0 z-[250] flex items-center justify-center p-4"
    role="dialog"
    aria-modal="true"
    aria-labelledby="confirm-modal-title"
>
    <div
        class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
        @click="$store.confirm.cancel()"
    ></div>

    <div
        x-show="$store.confirm.open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-6 sm:p-8"
        @click.stop
    >
        <div class="flex items-start gap-4">
            <span
                class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center"
                :class="{
                    'bg-red-100 text-red-600': $store.confirm.variant === 'danger',
                    'bg-amber-100 text-amber-600': $store.confirm.variant === 'warning',
                    'bg-sky-100 text-sky-600': $store.confirm.variant === 'info'
                }"
            >
                <template x-if="$store.confirm.variant === 'danger'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </template>
                <template x-if="$store.confirm.variant === 'warning' || $store.confirm.variant === 'info'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </template>
            </span>
            <div class="flex-1 min-w-0">
                <h2 id="confirm-modal-title" class="text-lg font-semibold text-slate-900" x-text="$store.confirm.title"></h2>
                <p class="mt-2 text-sm text-slate-600" x-text="$store.confirm.message" x-show="$store.confirm.message"></p>
                <div class="mt-6 flex flex-col-reverse sm:flex-row gap-3 sm:justify-end">
                    <button
                        type="button"
                        @click="$store.confirm.cancel()"
                        class="px-4 py-2.5 rounded-xl font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors duration-200"
                    >
                        <span x-text="$store.confirm.cancelText"></span>
                    </button>
                    <button
                        type="button"
                        @click="$store.confirm.accept()"
                        :class="{
                            'bg-red-600 hover:bg-red-700 text-white': $store.confirm.variant === 'danger',
                            'bg-amber-600 hover:bg-amber-700 text-white': $store.confirm.variant === 'warning',
                            'bg-sky-600 hover:bg-sky-700 text-white': $store.confirm.variant === 'info'
                        }"
                        class="px-4 py-2.5 rounded-xl font-medium transition-colors duration-200 shadow-sm"
                    >
                        <span x-text="$store.confirm.confirmText"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
