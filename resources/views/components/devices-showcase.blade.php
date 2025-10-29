@props([
    'screen' => 'login',
    'class' => '',
])

<div class="relative {{ $class }}" style="max-width: 1400px; margin: 0 auto;">
    <div class="relative w-full">
        <x-device-mockup 
            device="desktop" 
            :screen="$screen"
            class="w-full"
        />
    </div>

    <div class="absolute bottom-0 right-0 w-[28%]" style="transform: translateY(8%) translateX(-12%);">
        <x-device-mockup 
            device="mobile" 
            :screen="$screen"
            class="w-full drop-shadow-2xl"
        />
    </div>
</div>