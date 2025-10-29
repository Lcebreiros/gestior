@props([
    'disabled' => false,
    'checked' => false,
])

<label class="custom-checkbox-wrapper inline-flex items-center gap-3 cursor-pointer group">
    <div class="relative">
        <input 
            type="checkbox" 
            {{ $disabled ? 'disabled' : '' }}
            {{ $checked ? 'checked' : '' }}
            {!! $attributes->merge(['class' => 'custom-checkbox sr-only']) !!}
        >
        <div class="checkbox-box w-5 h-5 rounded-md border-2 border-white/20 bg-white/5 transition-all duration-200 flex items-center justify-center group-hover:border-white/30">
            <svg class="checkbox-check w-3.5 h-3.5 text-white opacity-0 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
    </div>
    
    <span class="text-sm text-gray-300 select-none">
        {{ $slot }}
    </span>
</label>

<style>
    .custom-checkbox:checked ~ .checkbox-box {
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(255, 255, 255, 0.3);
    }

    .custom-checkbox:checked ~ .checkbox-box .checkbox-check {
        opacity: 1;
    }

    .custom-checkbox:disabled ~ .checkbox-box {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .custom-checkbox-wrapper:has(.custom-checkbox:disabled) {
        cursor: not-allowed;
    }

    .custom-checkbox:focus ~ .checkbox-box {
        ring: 2px;
        ring-color: rgba(255, 255, 255, 0.1);
    }

    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border-width: 0;
    }
</style>