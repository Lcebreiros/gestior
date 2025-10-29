@php
    $user = auth()->user();
    $currentPlan = strtolower(optional($user)->subscription_level ?? '');
    $isLoggedIn = auth()->check();
@endphp

<x-dynamic-component :component="$isLoggedIn ? 'app-layout' : 'guest-layout'">
    <style>
        /* Plans page styles - Refined and minimalist */
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in-up { 
            animation: fade-in-up 0.6s ease-out forwards; 
            opacity: 0;
        }

        /* Pricing Cards */
        .pricing-card {
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 0%, rgba(255, 255, 255, 0.01) 100%);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 16px;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .pricing-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top, rgba(255, 255, 255, 0.04) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.35s ease;
            pointer-events: none;
        }

        .pricing-card:hover::before { opacity: 1; }

        .pricing-card:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.1);
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.04) 0%, rgba(255, 255, 255, 0.02) 100%);
        }

        .pricing-card.highlighted {
            border-color: rgba(255, 255, 255, 0.12);
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.04) 0%, rgba(255, 255, 255, 0.02) 100%);
        }

        .pricing-card.highlighted:hover {
            border-color: rgba(255, 255, 255, 0.16);
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.03) 100%);
        }

        .pricing-card.current { border-color: rgba(255, 255, 255, 0.12); }

        /* Feature Check Icons */
        .feature-check {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: rgb(16, 185, 129);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
        }

        /* Badges */
        .badge-current, .badge-popular {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.02em;
        }

        .badge-current {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.8);
        }

        .badge-popular {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.9);
        }

        /* Plan Buttons */
        .btn-plan {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 24px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #ffffff;
            letter-spacing: -0.01em;
        }

        .btn-plan:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }

        .btn-plan:active { transform: translateY(0); }

        .btn-plan--highlight {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.15);
            color: #ffffff;
        }

        .btn-plan--highlight:hover {
            background: rgba(255, 255, 255, 0.14);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-plan--enterprise {
            background: transparent;
            border-color: rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.9);
        }

        .btn-plan--enterprise:hover {
            background: rgba(255, 255, 255, 0.04);
            border-color: rgba(255, 255, 255, 0.18);
            color: #ffffff;
        }

        .btn-plan--disabled {
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.06);
            color: rgba(255, 255, 255, 0.3);
            cursor: not-allowed;
        }

        .btn-plan--disabled:hover {
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.06);
            transform: none;
        }

        /* Benefits Section */
        .benefit-section {
            border-radius: 16px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .benefit-card {
            background: rgba(0, 0, 0, 0.3);
            padding: 32px 24px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .benefit-card:hover { background: rgba(255, 255, 255, 0.03); }

        .benefit-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
        }

        .benefit-card:hover .benefit-icon {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.8);
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.02) 0%, rgba(255, 255, 255, 0.01) 100%);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 16px;
            padding: 48px 32px;
        }

        .btn-primary-enhanced {
            display: inline-flex !important;
            align-items: center;
            gap: 10px;
            padding: 16px 32px;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0.08) 100%) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
            font-size: 15px;
            font-weight: 500;
            text-decoration: none !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            letter-spacing: -0.01em;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .btn-primary-enhanced::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-primary-enhanced:hover::before { opacity: 1; }

        .btn-primary-enhanced:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.16) 0%, rgba(255, 255, 255, 0.12) 100%) !important;
            border-color: rgba(255, 255, 255, 0.2) !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
        }

        .btn-primary-enhanced:active {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-secondary {
            display: inline-flex !important;
            align-items: center;
            gap: 8px;
            padding: 16px 28px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: rgba(255, 255, 255, 0.8) !important;
            font-size: 15px;
            font-weight: 500;
            text-decoration: none !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            letter-spacing: -0.01em;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.06) !important;
            border-color: rgba(255, 255, 255, 0.12) !important;
            color: rgba(255, 255, 255, 0.95) !important;
            transform: translateY(-1px);
        }

        .btn-secondary:active { transform: translateY(0); }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @media (max-width: 1024px) {
            .pricing-card { margin-bottom: 0; }
        }

        @media (max-width: 768px) {
            .benefit-section { display: block; }
            .benefit-card { border-bottom: 1px solid rgba(255, 255, 255, 0.06); }
            .benefit-card:last-child { border-bottom: none; }
        }
    </style>

    @php
        
        // Definir características de cada plan
        $plans = [
            'basic' => [
                'name' => 'Basic',
                'tagline' => 'Ideal para empezar',
                'price' => 'Gratis',
                'period' => '',
                'highlighted' => false,
                'current' => $currentPlan === 'basic',
                'features' => [
                    '1 organización',
                    'Hasta 2 usuarios',
                    'Productos y servicios básicos',
                    'Soporte por email',
                    'Acceso web y móvil',
                    'Hasta 100 transacciones/mes'
                ],
                'button' => $currentPlan === 'basic' ? 'Plan actual' : ($isLoggedIn ? 'Seleccionar' : 'Comenzar'),
                'button_type' => $currentPlan === 'basic' ? 'disabled' : 'primary',
                'url' => $isLoggedIn ? '#' : route('register')
            ],
            'premium' => [
                'name' => 'Premium',
                'tagline' => 'Para equipos en crecimiento',
                'price' => '19',
                'period' => '/mes',
                'highlighted' => true,
                'current' => $currentPlan === 'premium',
                'features' => [
                    'Todo lo del Basic',
                    'Hasta 5 organizaciones',
                    'Usuarios ilimitados',
                    'Múltiples sucursales',
                    'Integraciones API',
                    'Soporte prioritario 24/7',
                    'Reportes avanzados',
                    'Hasta 1000 transacciones/mes'
                ],
                'button' => $currentPlan === 'premium' ? 'Plan actual' : ($isLoggedIn ? 'Seleccionar' : 'Comenzar'),
                'button_type' => $currentPlan === 'premium' ? 'disabled' : 'highlight',
                'url' => $isLoggedIn ? '#' : route('register')
            ],
            'enterprise' => [
                'name' => 'Enterprise',
                'tagline' => 'Escala sin límites',
                'price' => null,
                'period' => '',
                'highlighted' => false,
                'current' => $currentPlan === 'enterprise',
                'features' => [
                    'Todo lo del Premium',
                    'Organizaciones ilimitadas',
                    'SLA 99.9% de uptime',
                    'Implementación asistida',
                    'Soporte dedicado 24/7',
                    'Personalización completa',
                    'API ilimitada',
                    'Migración de datos',
                    'Entrenamiento incluido'
                ],
                'button' => $currentPlan === 'enterprise' ? 'Plan actual' : 'Contactar',
                'button_type' => $currentPlan === 'enterprise' ? 'disabled' : 'enterprise',
                'url' => 'mailto:ventas@gestior.com?subject=Consulta Plan Enterprise'
            ]
        ];
    @endphp

    <section class="min-h-screen bg-black py-24 md:py-32 text-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            
            <!-- Header -->
            <div class="text-center mb-20 animate-fade-in-up">
                <h1 class="text-5xl md:text-6xl font-extralight tracking-tight mb-5 text-white">
                    Planes y precios
                </h1>
                <p class="text-lg md:text-xl text-gray-400 max-w-2xl mx-auto font-light">
                    Elige el plan que mejor se adapte a tu negocio
                </p>
            </div>

            <!-- Indicador de plan actual -->
            @if($isLoggedIn && $currentPlan)
                <div class="mb-16 text-center animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-full bg-white/5 border border-white/10">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                        <span class="text-sm text-gray-400">
                            Tu plan: <span class="text-white font-medium capitalize">{{ $currentPlan }}</span>
                        </span>
                    </div>
                </div>
            @endif

            <!-- Planes -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-24 max-w-6xl mx-auto">
                @foreach($plans as $planKey => $plan)
                    <div class="animate-fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                        <div class="pricing-card h-full {{ $plan['highlighted'] ? 'highlighted' : '' }} {{ $plan['current'] ? 'current' : '' }}">
                            
                            <!-- Header -->
                            <div class="p-8 pb-6 border-b border-white/[0.06]">
                                <div class="flex items-start justify-between mb-4">
                                    <h3 class="text-xl font-medium text-white">{{ $plan['name'] }}</h3>
                                    @if($plan['current'])
                                        <div class="badge-current">
                                            Actual
                                        </div>
                                    @elseif($plan['highlighted'])
                                        <div class="badge-popular">
                                            Popular
                                        </div>
                                    @endif
                                </div>
                                
                                <p class="text-sm text-gray-500 mb-6">{{ $plan['tagline'] }}</p>

                                <!-- Precio -->
                                <div class="flex items-baseline gap-1.5">
                                    @if($plan['price'])
                                        <span class="text-gray-400 text-lg font-light">USD</span>
                                        <span class="text-5xl font-extralight text-white tracking-tight">
                                            {{ $plan['price'] }}
                                        </span>
                                        @if($plan['period'])
                                            <span class="text-gray-500 text-base font-light">{{ $plan['period'] }}</span>
                                        @endif
                                    @else
                                        <span class="text-4xl font-light text-white">Personalizado</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Características -->
                            <div class="p-8 flex-1">
                                <ul class="space-y-4">
                                    @foreach($plan['features'] as $feature)
                                        <li class="flex items-start gap-3">
                                            <div class="feature-check">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </div>
                                            <span class="text-[15px] text-gray-300 leading-relaxed font-light">{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Botón de acción -->
                            <div class="p-8 pt-0">
                                @if($plan['button_type'] === 'disabled')
                                    <button class="btn-plan btn-plan--disabled w-full" disabled>
                                        {{ $plan['button'] }}
                                    </button>
                                @else
                                    <a href="{{ $plan['url'] }}" 
                                       class="btn-plan w-full {{ $plan['button_type'] === 'highlight' ? 'btn-plan--highlight' : '' }} {{ $plan['button_type'] === 'enterprise' ? 'btn-plan--enterprise' : '' }}">
                                        {{ $plan['button'] }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Beneficios -->
            <div class="max-w-5xl mx-auto mb-24 animate-fade-in-up" style="animation-delay: 0.4s;">
                <div class="benefit-section">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-px bg-white/[0.06]">
                        <div class="benefit-card">
                            <div class="benefit-icon">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-white text-sm font-medium mb-1.5">30 días de garantía</h3>
                            <p class="text-gray-500 text-sm font-light leading-relaxed">Prueba sin riesgo y cancela cuando quieras</p>
                        </div>

                        <div class="benefit-card">
                            <div class="benefit-icon">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/>
                                </svg>
                            </div>
                            <h3 class="text-white text-sm font-medium mb-1.5">Soporte experto</h3>
                            <p class="text-gray-500 text-sm font-light leading-relaxed">Asistencia cuando la necesites</p>
                        </div>

                        <div class="benefit-card">
                            <div class="benefit-icon">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                                </svg>
                            </div>
                            <h3 class="text-white text-sm font-medium mb-1.5">Migración incluida</h3>
                            <p class="text-gray-500 text-sm font-light leading-relaxed">Te ayudamos con la transición</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Final -->
            <div class="max-w-3xl mx-auto text-center animate-fade-in-up" style="animation-delay: 0.5s;">
                <div class="cta-section">
                    
                    <h3 class="text-2xl font-light text-white mb-3">¿No estás seguro qué plan elegir?</h3>
                    <p class="text-gray-400 mb-8 max-w-xl mx-auto font-light leading-relaxed">
                        Habla con nuestro equipo y te ayudaremos a encontrar la solución perfecta para tu negocio
                    </p>
                    
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        <a href="#contact" class="btn-primary-enhanced group">
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/>
                            </svg>
                            Hablar con un especialista
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                        
                        <a href="mailto:ventas@gestior.com" class="btn-secondary group">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                            Enviar email
                        </a>
                    </div>
                    
                    <p class="text-gray-500 text-sm mt-6 font-light">
                        Respuesta en menos de 24 horas
                    </p>
                </div>
            </div>
        </div>
    </section>
</x-dynamic-component>