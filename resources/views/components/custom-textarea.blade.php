@props([
    'disabled' => false,
    'rows' => 4,
    'error' => null,
])

<div>
    <textarea {{ $disabled ? 'disabled' : '' }} rows="{{ $rows }}" {!! $attributes->merge([
        'class' => 'block w-full rounded-xl border-0 bg-white/[0.03] px-4 py-3 text-white placeholder:text-gray-500 focus:bg-white/[0.05] focus:ring-2 focus:ring-white/10 transition-all duration-200 resize-none ' .
        ($error ? 'ring-2 ring-red-500/30' : '')
    ]) !!}></textarea>

    @if($error)
        <p class="mt-2 text-sm text-red-400 flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            {{ $error }}
        </p>
    @endif
</div>

<style>
    textarea::placeholder {
        font-weight: 300;
    }

    textarea:hover:not(:disabled):not(:focus) {
        background: rgba(255, 255, 255, 0.04) !important;
    }

    textarea:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>