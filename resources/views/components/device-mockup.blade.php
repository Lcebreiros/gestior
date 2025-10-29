@props([
    'device' => 'desktop',
    'screen' => 'login',
    'class' => '',
])

@php
    $frameMap = [
        'desktop' => 'computer-frame.png',
        'mobile' => 'phone-frame.png'
    ];
    
    $contentPath = asset("images/mockups/content/{$device}/{$device}-{$screen}.png");
    $framePath = asset("images/mockups/frames/{$frameMap[$device]}");
    // Aspect ratios: desktop ~16:10 => 62.5%, mobile ~19.5:9 => 216.7%
    $paddingTop = $device === 'desktop' ? '62.5%' : '216.7%';
@endphp

<div class="relative {{ $class }}">
    <div style="width: 100%; padding-top: {{ $paddingTop }}; position: relative;">
        <img 
            src="{{ $contentPath }}"
            alt="{{ ucfirst($device) }} - {{ ucfirst($screen) }}"
            class="absolute inset-0 w-full h-full object-cover"
            style="z-index: 1;"
            loading="lazy"
        />
        
        <img 
            src="{{ $framePath }}"
            alt="{{ ucfirst($device) }} frame"
            class="absolute inset-0 w-full h-full object-contain"
            style="z-index: 2; pointer-events: none;"
            loading="lazy"
        />
    </div>
</div>
