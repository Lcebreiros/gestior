<x-guest-layout>

    <header class="relative text-white">

        <!-- PRIMERA SECCIÓN: Hero Network -->
        @include('components.landing.hero-network')
    </header>

    <!-- SEGUNDA SECCIÓN: Feature Sections -->
        @foreach(config('features.features') as $feature)
        <x-feature-section
            :badge="$feature['badge']"
            :title="$feature['title']"
            :description="$feature['description']"
            :features="$feature['features']"
            :image="$feature['image']"
            :inverted="$feature['inverted']"
            :background="$feature['background']"
        />
    @endforeach

    <!-- SEGUNDA SECCIÓN: Screenshots 3D -->
    @include('components.landing.screenshot-section')


    <!-- TERCERA SECCIÓN: Mockups de Dispositivos -->
    <section class="bg-black text-white py-20 screenshots-section">
        @livewire('mockup-view')
    </section>

<!-- CTA Final Section - Apple Marketing Style -->
<section id="cta-final" class="relative bg-black text-white py-20 md:py-32 overflow-hidden">
    <style>
        /* Apple-style premium background */
        .cta-apple-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(117, 52, 201, 0.2), transparent),
                radial-gradient(ellipse 60% 50% at 50% 120%, rgba(117, 52, 201, 0.15), transparent);
            pointer-events: none;
        }

        /* Elegant container - Apple style */
        .cta-apple-container {
            position: relative;
            z-index: 10;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .cta-apple-card {
            background: linear-gradient(
                135deg,
                rgba(255, 255, 255, 0.06) 0%,
                rgba(255, 255, 255, 0.02) 50%,
                rgba(255, 255, 255, 0.04) 100%
            );
            border: 1.5px solid rgba(255, 255, 255, 0.1);
            border-radius: 32px;
            padding: 3.5rem 3rem;
            backdrop-filter: blur(20px);
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .cta-apple-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.3) 50%,
                transparent
            );
        }

        .cta-apple-card:hover {
            border-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-4px);
            box-shadow: 
                0 40px 80px rgba(0, 0, 0, 0.3),
                0 0 60px rgba(117, 52, 201, 0.1);
        }

        /* Inner glow effect */
        .cta-apple-inner-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 500px;
            height: 500px;
            background: radial-gradient(
                circle,
                rgba(117, 52, 201, 0.08) 0%,
                transparent 70%
            );
            pointer-events: none;
            animation: glow-pulse 4s ease-in-out infinite;
        }

        @keyframes glow-pulse {
            0%, 100% { opacity: 0.6; transform: translate(-50%, -50%) scale(1); }
            50% { opacity: 0.8; transform: translate(-50%, -50%) scale(1.1); }
        }

        /* Typography - Apple style */
        .cta-apple-pretitle {
            font-size: 1.125rem;
            font-weight: 600;
            color: #7534c9;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .cta-apple-title {
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            font-weight: 700;
            line-height: 1.1;
            letter-spacing: -0.04em;
            text-align: center;
            margin-bottom: 1.5rem;
            background: linear-gradient(
                180deg,
                #ffffff 0%,
                rgba(255, 255, 255, 0.8) 100%
            );
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .cta-apple-subtitle {
            font-size: clamp(1.125rem, 2vw, 1.5rem);
            line-height: 1.5;
            color: rgba(255, 255, 255, 0.7);
            text-align: center;
            max-width: 650px;
            margin: 0 auto 2.5rem;
            font-weight: 400;
            letter-spacing: -0.02em;
        }

        /* Features grid - Apple style */
        .cta-apple-features {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2.5rem;
            margin: 2.5rem 0;
            flex-wrap: wrap;
        }

        .cta-apple-feature {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.625rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            font-weight: 500;
            letter-spacing: -0.01em;
        }

        .cta-apple-feature-icon {
            width: 44px;
            height: 44px;
            background: rgba(117, 52, 201, 0.12);
            border: 1px solid rgba(117, 52, 201, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .cta-apple-feature:hover .cta-apple-feature-icon {
            background: rgba(117, 52, 201, 0.18);
            border-color: rgba(117, 52, 201, 0.3);
            transform: translateY(-2px);
        }

        .cta-apple-feature-icon svg {
            width: 22px;
            height: 22px;
            color: #7534c9;
        }

        /* CTA Button - Apple style */
        .cta-apple-button-wrapper {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 3rem;
            flex-wrap: wrap;
        }

        .cta-apple-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 1.25rem 3.5rem;
            background: #7534c9;
            color: #ffffff;
            font-size: 1.125rem;
            font-weight: 600;
            border-radius: 14px;
            letter-spacing: -0.02em;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            border: none;
            cursor: pointer;
            box-shadow: 
                0 4px 16px rgba(117, 52, 201, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .cta-apple-button:hover {
            background: #6428b0;
            transform: translateY(-2px);
            box-shadow: 
                0 8px 24px rgba(117, 52, 201, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
        }

        .cta-apple-button:active {
            transform: translateY(0);
            box-shadow: 
                0 2px 8px rgba(117, 52, 201, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .cta-apple-button svg {
            width: 20px;
            height: 20px;
            transition: transform 0.3s ease;
        }

        .cta-apple-button:hover svg {
            transform: translateX(3px);
        }

        /* Support text - Apple style */
        .cta-apple-support {
            text-align: center;
            margin-top: 2.5rem;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.45);
            font-weight: 400;
            letter-spacing: -0.01em;
        }

        .cta-apple-support-highlight {
            color: rgba(255, 255, 255, 0.65);
            font-weight: 500;
        }

        /* Divider */
        .cta-apple-divider {
            height: 1px;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.1) 50%,
                transparent
            );
            margin: 3rem 0;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .cta-apple-card {
                padding: 4rem 3rem;
                border-radius: 28px;
            }

            .cta-apple-features {
                gap: 2rem;
            }
        }

        @media (max-width: 768px) {
            .cta-apple-card {
                padding: 3rem 2rem;
                border-radius: 24px;
            }

            .cta-apple-pretitle {
                font-size: 0.95rem;
            }

            .cta-apple-features {
                flex-direction: column;
                gap: 1.5rem;
            }

            .cta-apple-button-wrapper {
                flex-direction: column;
                width: 100%;
            }

            .cta-apple-button {
                width: 100%;
                padding: 1.125rem 2.5rem;
            }
        }

        @media (max-width: 480px) {
            .cta-apple-card {
                padding: 2.5rem 1.5rem;
            }

            .cta-apple-pretitle {
                font-size: 0.875rem;
                letter-spacing: 0.08em;
            }
        }
    </style>

    <!-- Background -->
    <div class="cta-apple-bg"></div>

    <!-- Content -->
    <div class="cta-apple-container">
        <div class="cta-apple-card">
            
            <!-- Inner glow -->
            <div class="cta-apple-inner-glow"></div>

            <!-- Content wrapper -->
            <div style="position: relative; z-index: 1;">
                
                <!-- Pre-title -->
                <div class="cta-apple-pretitle">
                    Comienza hoy
                </div>

                <!-- Main title -->
                <h2 class="cta-apple-title">
                    Probá Gestior ahora
                </h2>

                <!-- Subtitle -->
                <p class="cta-apple-subtitle">
                    Gestiona pedidos, stock y equipo desde una plataforma diseñada para que tu negocio crezca sin límites.
                </p>

                <!-- CTA Button -->
                <div class="cta-apple-button-wrapper">
                    <a href="{{ route('register') }}" class="cta-apple-button">
                        <span>Comienza gratis</span>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>

                <!-- Support text -->
                <div class="cta-apple-support">
                    <span class="cta-apple-support-highlight">primer mes gratis</span>
                    <span> • </span>
                    <span>Sin compromisos</span>
                    <span> • </span>
                    <span>Cancela cuando quieras</span>
                </div>

            </div>
        </div>
    </div>
 </section>

    {{-- Footer --}}
    @include('components.landing.footer')

    <script>
        // Sincronizar animaciones entre HERO y screenshots
        document.addEventListener('DOMContentLoaded', function() {
            const heroSection = document.querySelector('.hero-intro-section');
            const screenshotsSection = document.querySelector('.screenshots-section');
            const HERO_EXIT_THRESHOLD = 0.3;
            const SCREENSHOTS_ENTRY_THRESHOLD = 0.6;

            function syncAnimations() {
                const windowHeight = window.innerHeight;

                if (heroSection) {
                    const heroRect = heroSection.getBoundingClientRect();
                    const heroTop = heroRect.top;
                    const heroHeight = heroRect.height;
                    if (heroTop < -heroHeight * HERO_EXIT_THRESHOLD) {
                        heroSection.classList.add('exit');
                    } else {
                        heroSection.classList.remove('exit');
                    }
                }

                if (screenshotsSection) {
                    const screenshotsRect = screenshotsSection.getBoundingClientRect();
                    const screenshotsTop = screenshotsRect.top;
                    const screenshotsHeight = screenshotsRect.height;
                    if (screenshotsTop < windowHeight * SCREENSHOTS_ENTRY_THRESHOLD && screenshotsTop > -screenshotsHeight * 0.3) {
                        screenshotsSection.classList.add('visible');
                        screenshotsSection.classList.remove('exit');
                    } else if (screenshotsTop < -screenshotsHeight * 0.3) {
                        screenshotsSection.classList.remove('visible');
                        screenshotsSection.classList.add('exit');
                    } else if (screenshotsTop > windowHeight * 0.8) {
                        screenshotsSection.classList.remove('visible', 'exit');
                    }
                }
            }

            window.addEventListener('scroll', syncAnimations);
            window.addEventListener('resize', syncAnimations);
            syncAnimations();
            setTimeout(syncAnimations, 100);
        });
    </script>
 </x-guest-layout>
