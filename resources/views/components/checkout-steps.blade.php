@props(['current' => 1])

@php
$steps = [
    1 => ['label' => 'Carrito'],
    2 => ['label' => 'Datos'],
    3 => ['label' => 'Confirmación'],
    4 => ['label' => 'Pago'],
];

// Determine if $current is an Alpine variable name (string) or a static value (numeric)
$isAlpine = is_string($current) && !is_numeric($current);
@endphp

<div class="w-full flex items-center justify-center py-8 px-4">
    <div class="flex items-center w-full max-w-3xl">
        @foreach($steps as $step => $info)
            {{-- Step Circle --}}
            <div class="flex flex-col items-center">
                <div 
                    @if($isAlpine)
                        :class="{{ $current }} > {{ $step }} ? 'bg-violet-600 text-white' : ({{ $current }} === {{ $step }} ? 'bg-violet-600 text-white ring-4 ring-violet-100 shadow-lg shadow-violet-200' : 'bg-white border-2 border-slate-200 text-slate-400')"
                    @else
                        class="w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center font-black text-sm transition-all duration-500 
                        {{ $step < $current ? 'bg-violet-600 text-white' : ($step == $current ? 'bg-violet-600 text-white ring-4 ring-violet-100 shadow-lg shadow-violet-200' : 'bg-white border-2 border-slate-200 text-slate-400') }}"
                    @endif
                    @if($isAlpine) class="w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center font-black text-sm transition-all duration-500" @endif
                >
                    @if($isAlpine)
                        <template x-if="{{ $current }} > {{ $step }}">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </template>
                        <template x-if="{{ $current }} <= {{ $step }}">
                            <span x-text="{{ $step }}"></span>
                        </template>
                    @else
                        @if($step < $current)
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            {{ $step }}
                        @endif
                    @endif
                </div>

                {{-- Label --}}
                <span 
                    @if($isAlpine)
                        :class="{{ $current }} >= {{ $step }} ? 'text-slate-900' : 'text-slate-400'"
                    @endif
                    class="mt-3 text-[10px] sm:text-xs font-black uppercase tracking-widest text-center transition-colors duration-500
                    @if(!$isAlpine)
                        {{ $step <= $current ? 'text-slate-900' : 'text-slate-400' }}
                    @endif
                ">
                    {{ $info['label'] }}
                </span>
            </div>

            {{-- Connector Line --}}
            @if($step < count($steps))
                <div 
                    @if($isAlpine)
                        :class="{{ $current }} > {{ $step }} ? 'bg-violet-600' : 'bg-slate-200'"
                    @else
                        class="flex-1 h-1 mx-2 sm:mx-4 rounded-full transition-all duration-700
                        {{ $step < $current ? 'bg-violet-600' : 'bg-slate-200' }}"
                    @endif
                    @if($isAlpine) class="flex-1 h-1 mx-2 sm:mx-4 rounded-full transition-all duration-700" @endif
                ></div>
            @endif
        @endforeach
    </div>
</div>
