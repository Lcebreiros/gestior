<!-- Support Section - Apple Minimalist Style -->
<section id="support" class="relative bg-black text-white py-32 md:py-40 overflow-hidden">
    <style>
        /* Subtle background gradients */
        .support-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(ellipse 100% 70% at 50% 0%, rgba(99, 102, 241, 0.06), transparent 50%),
                radial-gradient(ellipse 100% 70% at 50% 100%, rgba(117, 52, 201, 0.04), transparent 50%);
            pointer-events: none;
        }

        /* Main container */
        .support-container {
            position: relative;
            z-index: 10;
            max-width: 1120px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Header - Apple style */
        .support-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.875rem;
            background: rgba(255, 255, 255, 0.04);
            border: 0.5px solid rgba(255, 255, 255, 0.08);
            border-radius: 100px;
            font-size: 0.8125rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.6);
            letter-spacing: 0.02em;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(20px);
        }

        .support-title {
            font-size: clamp(2.75rem, 6vw, 4.5rem);
            font-weight: 600;
            line-height: 1.05;
            letter-spacing: -0.025em;
            margin-bottom: 1.25rem;
            color: #ffffff;
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "Segoe UI", Roboto, sans-serif;
        }

        .support-subtitle {
            font-size: clamp(1.25rem, 2.5vw, 1.5rem);
            line-height: 1.5;
            color: rgba(255, 255, 255, 0.5);
            max-width: 680px;
            margin-bottom: 5rem;
            font-weight: 400;
            letter-spacing: -0.015em;
        }

        /* Main channels - Apple minimal cards */
        .support-channels {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 4rem;
        }

        .support-channel-card {
            background: rgba(255, 255, 255, 0.03);
            border: 0.5px solid rgba(255, 255, 255, 0.1);
            border-radius: 28px;
            padding: 3rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(40px);
        }

        .support-channel-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(
                800px circle at var(--mouse-x, 50%) var(--mouse-y, 50%),
                rgba(99, 102, 241, 0.06),
                transparent 40%
            );
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .support-channel-card:hover {
            border-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-4px) scale(1.01);
            background: rgba(255, 255, 255, 0.04);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
        }

        .support-channel-card:hover::before {
            opacity: 1;
        }

        .support-channel-icon {
            width: 56px;
            height: 56px;
            background: rgba(255, 255, 255, 0.05);
            border: 0.5px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 1;
        }

        .support-channel-card:hover .support-channel-icon {
            background: rgba(99, 102, 241, 0.08);
            border-color: rgba(99, 102, 241, 0.2);
            transform: scale(1.05);
        }

        .support-channel-icon svg {
            width: 28px;
            height: 28px;
            color: rgba(255, 255, 255, 0.7);
            transition: color 0.3s ease;
        }

        .support-channel-card:hover .support-channel-icon svg {
            color: #a5b4fc;
        }

        .support-channel-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: rgba(34, 197, 94, 0.08);
            border: 0.5px solid rgba(34, 197, 94, 0.15);
            border-radius: 100px;
            font-size: 0.6875rem;
            font-weight: 500;
            color: rgba(74, 222, 128, 0.9);
            letter-spacing: 0.03em;
            margin-bottom: 1.25rem;
        }

        .support-channel-title {
            font-size: 1.625rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.875rem;
            letter-spacing: -0.015em;
            position: relative;
            z-index: 1;
        }

        .support-channel-description {
            font-size: 1.0625rem;
            line-height: 1.55;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 2rem;
            font-weight: 400;
            letter-spacing: -0.01em;
            position: relative;
            z-index: 1;
        }

        .support-channel-action {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            background: rgba(255, 255, 255, 0.04);
            border: 0.5px solid rgba(255, 255, 255, 0.1);
            border-radius: 100px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9375rem;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            position: relative;
            z-index: 1;
        }

        .support-channel-action:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            transform: translateX(2px);
        }

        .support-channel-action svg {
            width: 14px;
            height: 14px;
            transition: transform 0.3s ease;
        }

        .support-channel-action:hover svg {
            transform: translateX(2px);
        }

        /* Additional Features Grid - Minimal cards */
        .support-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.25rem;
            margin-bottom: 4rem;
        }

        .support-feature-card {
            background: rgba(255, 255, 255, 0.02);
            border: 0.5px solid rgba(255, 255, 255, 0.06);
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(20px);
        }

        .support-feature-card:hover {
            border-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.03);
        }

        .support-feature-icon {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.04);
            border: 0.5px solid rgba(255, 255, 255, 0.06);
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .support-feature-card:hover .support-feature-icon {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .support-feature-icon svg {
            width: 22px;
            height: 22px;
            color: rgba(255, 255, 255, 0.6);
        }

        .support-feature-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.625rem;
            letter-spacing: -0.015em;
        }

        .support-feature-description {
            font-size: 0.9375rem;
            line-height: 1.5;
            color: rgba(255, 255, 255, 0.45);
            font-weight: 400;
            letter-spacing: -0.01em;
        }

        /* Response Time Banner - Apple minimal */
        .support-banner {
            background: rgba(255, 255, 255, 0.02);
            border: 0.5px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 3.5rem 2.5rem;
            text-align: center;
            margin-top: 4rem;
            backdrop-filter: blur(40px);
        }

        .support-banner-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 2.5rem;
            letter-spacing: -0.02em;
        }

        .support-banner-stats {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5rem;
            flex-wrap: wrap;
        }

        .support-banner-stat {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }

        .support-banner-stat-value {
            font-size: 2.5rem;
            font-weight: 600;
            color: #ffffff;
            letter-spacing: -0.03em;
        }

        .support-banner-stat-label {
            font-size: 0.9375rem;
            color: rgba(255, 255, 255, 0.5);
            font-weight: 400;
            letter-spacing: -0.01em;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .support-container {
                padding: 0 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .support-channels {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }

            .support-features {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .support-channel-card {
                padding: 2.5rem;
            }

            .support-feature-card {
                padding: 1.75rem;
            }

            .support-banner {
                padding: 2.5rem 2rem;
            }

            .support-banner-stats {
                gap: 3rem;
            }

            .support-title {
                margin-bottom: 1rem;
            }

            .support-subtitle {
                margin-bottom: 3.5rem;
            }
        }

        @media (max-width: 480px) {
            .support-channel-card {
                padding: 2rem;
            }

            .support-banner {
                padding: 2rem 1.5rem;
            }

            .support-banner-stats {
                flex-direction: column;
                gap: 2rem;
            }

            .support-container {
                padding: 0 1.25rem;
            }
        }
    </style>

    <!-- Background -->
    <div class="support-bg"></div>

    <!-- Content -->
    <div class="support-container">

        <!-- Header -->
        <div style="text-align: center; margin-bottom: 4rem;">
            <div class="support-badge">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/>
                </svg>
                <span>Soporte y Asistencia</span>
            </div>

            <h2 class="support-title">
                Estamos para ayudarte,<br>cuando lo necesites
            </h2>
        </div>

        <!-- Main Support Channels -->
        <div class="support-channels">

            <!-- Panel Support -->
            <div class="support-channel-card">
                <span class="support-channel-badge">24/7 Disponible</span>
                <div class="support-channel-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/>
                    </svg>
                </div>
                <h3 class="support-channel-title">Soporte en el Panel</h3>
                <p class="support-channel-description">
                    Accedé al chat de soporte integrado directamente desde tu panel de control. Respuestas en tiempo real para resolver tus dudas al instante.
                </p>
                <a href="https://panel.gestior.com.ar" class="support-channel-action">
                    <span>Ir al panel</span>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>

            <!-- Email Support -->
            <div class="support-channel-card">
                <span class="support-channel-badge">Respuesta en 24hs</span>
                <div class="support-channel-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                    </svg>
                </div>
                <h3 class="support-channel-title">Soporte por Email</h3>
                <p class="support-channel-description">
                    Envianos un email con tu consulta detallada. Nuestro equipo te responderá con soluciones completas en menos de 24 horas.
                </p>
                <a href="mailto:soporte@gestior.com.ar" class="support-channel-action">
                    <span>soporte@gestior.com.ar</span>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>

        </div>

        <!-- Additional Features -->
        <div class="support-features">

            <!-- Feature 3 -->
            <div class="support-feature-card">
                <div class="support-feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/>
                    </svg>
                </div>
                <h3 class="support-feature-title">Asistencia Técnica</h3>
                <p class="support-feature-description">
                    Ayuda especializada para configuraciones avanzadas, integraciones y personalización.
                </p>
            </div>

            <!-- Feature 4 -->
            <div class="support-feature-card">
                <div class="support-feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                    </svg>
                </div>
                <h3 class="support-feature-title">Actualizaciones Continuas</h3>
                <p class="support-feature-description">
                    Mejoras constantes de la plataforma con soporte incluido para todas las nuevas funcionalidades.
                </p>
            </div>

        </div>

        <!-- Response Time Banner -->
        <div class="support-banner">
            <h3 class="support-banner-title">Tiempos de respuesta garantizados</h3>
            <div class="support-banner-stats">
                <div class="support-banner-stat">
                    <div class="support-banner-stat-value">&lt; 5 minutos</div>
                    <div class="support-banner-stat-label">Chat en vivo</div>
                </div>
                <div class="support-banner-stat">
                    <div class="support-banner-stat-value">&lt; 1 hora</div>
                    <div class="support-banner-stat-label">Email</div>
                </div>
            </div>
        </div>

    </div>
</section>
