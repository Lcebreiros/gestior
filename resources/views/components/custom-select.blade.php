@props([
    'disabled' => false,
    'error' => null,
    'placeholder' => 'Seleccionar...',
])

<div class="relative">
    <select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
        'class' => 'custom-select block w-full rounded-xl border-0 bg-white/[0.03] px-4 py-3 pr-10 text-white focus:bg-white/[0.05] focus:ring-2 focus:ring-white/10 transition-all duration-200 appearance-none cursor-pointer ' .
        ($error ? 'border-red-500/30 focus:ring-red-500/20' : '')
    ]) !!}>
        @if($placeholder)
            <option value="" disabled selected class="bg-gray-900 text-gray-400">{{ $placeholder }}</option>
        @endif
        {{ $slot }}
    </select>
    
    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>
</div>

@if($error)
    <p class="mt-2 text-sm text-red-400 flex items-center gap-1.5">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
        {{ $error }}
    </p>
@endif

<style>
    .custom-select {
        font-size: 15px;
        letter-spacing: -0.01em;
    }

    .custom-select option {
        background: #1a1a1a;
        color: white;
        padding: 8px;
    }

    .custom-select:hover:not(:disabled):not(:focus) {
        background: rgba(255, 255, 255, 0.04);
    }

    .custom-select:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>