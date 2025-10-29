@props([
    'value' => null,
    'required' => false,
])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-gray-300 mb-2']) }}>
    {{ $value ?? $slot }}
    @if($required)
        <span class="text-red-400 ml-1">*</span>
    @endif
</label>