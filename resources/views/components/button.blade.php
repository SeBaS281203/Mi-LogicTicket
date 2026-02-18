@props([
    'variant' => 'primary', // primary | secondary | ghost
    'size' => 'md',         // sm | md | lg
    'href' => null,
    'type' => 'button',
])

@php
    $isLink = $href !== null;
    $base = 'inline-flex items-center justify-center gap-2 font-semibold rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-60 disabled:pointer-events-none';
    $sizes = [
        'sm' => 'px-4 py-2 text-sm',
        'md' => 'px-5 py-2.5 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];
    $variants = [
        'primary' => 'bg-brand text-white hover:bg-brand-hover focus:ring-brand/40',
        'secondary' => 'bg-white border border-neutral-200 text-neutral-700 hover:bg-neutral-50 hover:border-neutral-300 focus:ring-neutral-300',
        'ghost' => 'text-neutral-700 hover:bg-neutral-100 focus:ring-neutral-200',
    ];
    $classes = $base . ' ' . ($sizes[$size] ?? $sizes['md']) . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if($isLink)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
