@props([
    'id' => '',
    'name' => '',
    'label' => '',
    'type' => 'text',
    'placeholder' => '',
    'value' => '',
    'required' => false,
    'autocomplete' => null,
    'icon' => 'none', // envelope | lock | none
])

@php
    $hasError = $errors->has($name);
    $inputClass = 'w-full h-12 pl-11 pr-4 rounded-xl border border-neutral-200 bg-white text-[#1f2937] placeholder-neutral-400 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2 focus:border-brand ' . ($hasError ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '');
@endphp

<div {{ $attributes->only('class')->merge(['class' => '']) }}>
    @if($label)
        <label for="{{ $id ?: $name }}" class="block text-sm font-medium text-[#1f2937] mb-1.5">{{ $label }}</label>
    @endif
    <div class="relative">
        @if($icon !== 'none')
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400 pointer-events-none" aria-hidden="true">
                @if($icon === 'envelope')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                @elseif($icon === 'lock')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                @endif
            </span>
        @endif
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $id ?: $name }}"
            value="{{ $value ?? old($name) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $autocomplete ? 'autocomplete=' . $autocomplete : '' }}
            {{ $attributes->except('class')->merge(['class' => $inputClass]) }}
        />
        {{ $slot }}
    </div>
    @error($name)
        <p class="mt-1.5 text-sm text-red-600" role="alert">{{ $message }}</p>
    @enderror
</div>
