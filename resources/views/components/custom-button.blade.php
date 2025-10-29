@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'iconPosition' => 'left',
    'loading' => false,
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 font-medium rounded-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $sizeClasses = [
        'sm' => 'px-4 py-2 text-sm',
        'md' => 'px-6 py-3 text-[15px]',
        'lg' => 'px-8 py-4 text-base',
    ];
    
    $variantClasses = [
        'primary' => 'bg-white/[0.12] border border-white/[0.15] text-white hover:bg-white/[0.16] hover:border-white/[0.2] hover:shadow-lg hover:-translate-y-0.5',
        'secondary' => 'bg-white/[0.03] border border-white/[0.08] text-white/80 hover:bg-white/[0.06] hover:border-white/[0.12] hover:text-white hover:-translate-y-0.5',
        'danger' => 'bg-red-500/[0.08] border border-red-500/[0.2] text-red-400 hover:bg-red-500/[0.12] hover:border-red-500/[0.3] hover:-translate-y-0.5',
        'ghost' => 'bg-transparent border border-transparent text-white/70 hover:bg-white/[0.05] hover:text-white',
    ];
    
    $classes = $baseClasses . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']) . ' ' . ($variantClasses[$variant] ?? $variantClasses['primary']);
@endphp

<button {{ $attributes->merge(['type' => 'button', 'class' => $classes]) }}>
    @if($loading)
        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @elseif($icon && $iconPosition === 'left')
        {!! $icon !!}
    @endif
    
    {{ $slot }}
    
    @if($icon && $iconPosition === 'right' && !$loading)
        {!! $icon !!}
    @endif
</button>

<style>
    button:active:not(:disabled) {
        transform: scale(0.98);
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }
</style>