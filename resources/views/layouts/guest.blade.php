<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        <style>
            :root {
                --violet-700: #7e22ce;
                --violet-800: #6b21a8;
                --violet-900: #581c87;
                --violet-950: #2a0b47;
            }

            body {
                background: #000000;
                color: #ffffff;
            }

            .auth-hero-bg {
                background: radial-gradient(ellipse at top, rgba(124, 58, 237, 0.15) 0%, #000000 50%);
                min-height: 100vh;
                position: relative;
                overflow: hidden;
            }

            .btn-primary {
                background: linear-gradient(135deg, #7c3aed, #6d28d9) !important;
                box-shadow: 0 4px 16px rgba(124, 58, 237, 0.3);
                transition: all 0.3s ease;
                border: none !important;
                color: #fff !important;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 24px rgba(124, 58, 237, 0.5);
            }

            .btn-glass {
                background: rgba(255, 255, 255, 0.08) !important;
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.1) !important;
                transition: all 0.3s ease;
                color: #fff !important;
            }

            .btn-glass:hover {
                background: rgba(255, 255, 255, 0.15) !important;
                border-color: rgba(255, 255, 255, 0.2) !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-black text-white">
            @livewire('navigation-menu')

            @if (isset($header))
                <header class="page-header bg-transparent border-b border-white/10">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="pb-12">
                {{ $slot }}
            </main>
        </div>

        @livewireScripts
    </body>
</html>
