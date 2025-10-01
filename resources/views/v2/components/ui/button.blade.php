@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'icon' => null,
    'href' => null
])

@php
    $baseClasses =
        'inline-flex items-center justify-center font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200';
    $variantClasses = [
        'primary' => 'bg-primary-500 text-slate-800 hover:bg-primary-600 focus:ring-primary-500',
        'secondary' => 'bg-primary-500 text-slate-800 hover:bg-primary-600 focus:ring-primary-500',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
        'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500',
        'outline' =>
            'border border-primary-500 bg-primary-500 text-slate-800 hover:bg-primary-600 focus:ring-primary-500'
    ];
    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base'
    ];
    $classes = $baseClasses . ' ' . ($variantClasses[$variant] ?? '') . ' ' . ($sizeClasses[$size] ?? '');
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)
            <i class="{{ $icon }} {{ isset($slot) && $slot->isNotEmpty() ? 'mr-2' : '' }}"></i>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)
            <i class="{{ $icon }} {{ isset($slot) && $slot->isNotEmpty() ? 'mr-2' : '' }}"></i>
        @endif
        {{ $slot }}
    </button>
@endif
