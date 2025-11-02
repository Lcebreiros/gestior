<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Gestior') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles

        <style>
            :root {
                --violet-50: #faf5ff;
                --violet-100: #f3e8ff;
                --violet-200: #e9d5ff;
                --violet-300: #d8b4fe;
                --violet-400: #c084fc;
                --violet-500: #a855f7;
                --violet-600: #9333ea;
                --violet-700: #7e22ce;
                --violet-800: #6b21a8;
                --violet-900: #581c87;
                --dark-bg: #000000;
                --dark-surface: #15151f;
                --dark-border: rgba(255, 255, 255, 0.08);
            }
            
            body {
                font-family: 'Inter', sans-serif;
                letter-spacing: -0.011em;
                background: var(--dark-bg);
            }

            /* Scrollbar personalizado - tema oscuro */
            ::-webkit-scrollbar {
                width: 10px;
                height: 10px;
            }
            ::-webkit-scrollbar-track {
                background: #1a1a24;
            }
            ::-webkit-scrollbar-thumb {
                background: #2d2d3d;
                border-radius: 5px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #3d3d4d;
            }

            /* Banner de verificación mejorado */
            .verification-banner {
                background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(217, 119, 6, 0.1) 100%);
                border-left: 4px solid #f59e0b;
            }

            /* Mejoras de botones */
            .btn-primary {
                background: linear-gradient(135deg, var(--violet-600) 0%, var(--violet-700) 100%);
                transition: all 0.2s ease;
            }
            .btn-primary:hover {
                background: linear-gradient(135deg, var(--violet-700) 0%, var(--violet-800) 100%);
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4);
            }

            /* Headers mejorados - tema oscuro */
            .page-header {
                background: linear-gradient(to bottom, #15151f 0%, #0f0f17 100%);
                border-bottom: 1px solid var(--dark-border);
            }

            /* Cards con efecto de elevación - tema oscuro */
            .card-elevated {
                background: var(--dark-surface);
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3), 0 1px 2px 0 rgba(0, 0, 0, 0.2);
                transition: box-shadow 0.2s ease;
                border: 1px solid var(--dark-border);
            }
            .card-elevated:hover {
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.4), 0 4px 6px -2px rgba(0, 0, 0, 0.3);
            }

            /* Success/Error messages mejorados - tema oscuro */
            .alert-success {
                background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(16, 185, 129, 0.1) 100%);
                border-left: 4px solid #10b981;
                color: #86efac;
            }

            .alert-error {
                background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(239, 68, 68, 0.1) 100%);
                border-left: 4px solid #ef4444;
                color: #fca5a5;
            }

            /* Animación suave para transiciones */
            * {
                transition-property: background-color, border-color, color, fill, stroke;
                transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
                transition-duration: 150ms;
            }

            /* Mejoras adicionales para tema oscuro */
            .min-h-screen {
                background: var(--dark-bg);
            }

            /* Texto general */
            .text-gray-900 {
                color: #f3f4f6 !important;
            }
            
            .text-gray-700 {
                color: #d1d5db !important;
            }
            
            .text-gray-600 {
                color: #9ca3af !important;
            }
            
            .text-gray-500 {
                color: #6b7280 !important;
            }

            /* Backgrounds oscuros */
            .bg-white {
                background-color: var(--dark-surface) !important;
            }

            .bg-gray-50 {
                background-color: #15151f !important;
            }

            .bg-gray-100 {
                background-color: #1a1a24 !important;
            }

            /* Bordes */
            .border-gray-200 {
                border-color: var(--dark-border) !important;
            }

            .border-gray-300 {
                border-color: rgba(255, 255, 255, 0.12) !important;
            }

            /* Inputs y forms */
            input, textarea, select {
                background-color: var(--dark-surface) !important;
                border-color: var(--dark-border) !important;
                color: #f3f4f6 !important;
            }

            input:focus, textarea:focus, select:focus {
                border-color: var(--violet-600) !important;
                ring-color: var(--violet-600) !important;
            }

            /* Hover states */
            .hover\:bg-gray-50:hover {
                background-color: rgba(255, 255, 255, 0.05) !important;
            }

            .hover\:bg-gray-100:hover {
                background-color: rgba(255, 255, 255, 0.08) !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-black">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="page-header bg-white dark:bg-gray-800 shadow-sm">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Flash Messages -->
            @if (session('success') || session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                    @if (session('success'))
                        <div class="alert-success rounded-lg px-4 py-3 shadow-lg flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="font-medium">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert-error rounded-lg px-4 py-3 shadow-lg flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <p class="font-medium">{{ session('error') }}</p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Page Content -->
            <main class="pb-12">
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
