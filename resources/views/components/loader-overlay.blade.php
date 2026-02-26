@props([])

<div
    x-show="$store.loader.visible"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-cloak
    class="fixed inset-0 z-[300] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm"
    role="alert"
    aria-busy="true"
    aria-label="Cargando"
>
    <div class="flex flex-col items-center gap-4 p-8 rounded-2xl bg-white shadow-2xl">
        <div class="relative">
            <div class="w-14 h-14 border-4 border-emerald-200 rounded-full"></div>
            <div class="absolute inset-0 w-14 h-14 border-4 border-transparent border-t-emerald-600 rounded-full animate-spin"></div>
            <div class="absolute inset-0 w-14 h-14 border-4 border-transparent border-t-emerald-500 rounded-full animate-spin" style="animation-duration: 1.5s; animation-direction: reverse;"></div>
        </div>
        <p class="text-sm font-medium text-slate-600 animate-pulse">Cargando...</p>
    </div>
</div>
