<!-- Security Section - Apple Minimalist Style -->
<section id="security" class="relative bg-black text-white py-32 md:py-40 overflow-hidden">
    <style>
        /* Subtle background gradients */
        .security-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(ellipse 100% 70% at 50% 0%, rgba(117, 52, 201, 0.06), transparent 50%),
                radial-gradient(ellipse 100% 70% at 50% 100%, rgba(99, 102, 241, 0.04), transparent 50%);
            pointer-events: none;
        }

        /* Main container */
        .security-container {
            position: relative;
            z-index: 10;
            max-width: 1120px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Header - Apple style */
        .security-badge {
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

        .security-title {
            font-size: clamp(2.75rem, 6vw, 4.5rem);
            font-weight: 600;
            line-height: 1.05;
            letter-spacing: -0.025em;
            margin-bottom: 1.25rem;
            color: #ffffff;
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "Segoe UI", Roboto, sans-serif;
        }

        .security-subtitle {
            font-size: clamp(1.25rem, 2.5vw, 1.5rem);
            line-height: 1.5;
            color: rgba(255, 255, 255, 0.5);
            max-width: 680px;
            margin-bottom: 5rem;
            font-weight: 400;
            letter-spacing: -0.015em;
        }

        /* Features Grid - Apple minimal cards */
        .security-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.25rem;
            margin-bottom: 4rem;
        }

        .security-card {
            background: rgba(255, 255, 255, 0.02);
            border: 0.5px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 2.5rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(40px);
        }

        .security-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(
                600px circle at var(--mouse-x, 50%) var(--mouse-y, 50%),
                rgba(117, 52, 201, 0.05),
                transparent 40%
            );
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .security-card:hover {
            border-color: rgba(255, 255, 255, 0.12);
            transform: translateY(-3px) scale(1.005);
            background: rgba(255, 255, 255, 0.03);
        }

        .security-card:hover::before {
            opacity: 1;
        }

        .security-card-icon {
            width: 52px;
            height: 52px;
            background: rgba(255, 255, 255, 0.04);
            border: 0.5px solid rgba(255, 255, 255, 0.08);
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.75rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 1;
        }

        .security-card:hover .security-card-icon {
            background: rgba(117, 52, 201, 0.08);
            border-color: rgba(117, 52, 201, 0.15);
            transform: scale(1.05);
        }

        .security-card-icon svg {
            width: 26px;
            height: 26px;
            color: rgba(255, 255, 255, 0.65);
            transition: color 0.3s ease;
        }

        .security-card:hover .security-card-icon svg {
            color: #9b87f5;
        }

        .security-card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.75rem;
            letter-spacing: -0.015em;
            position: relative;
            z-index: 1;
        }

        .security-card-description {
            font-size: 0.9375rem;
            line-height: 1.55;
            color: rgba(255, 255, 255, 0.45);
            font-weight: 400;
            letter-spacing: -0.01em;
            position: relative;
            z-index: 1;
        }

        /* Trust indicators - Apple minimal */
        .security-trust {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 4rem;
            flex-wrap: wrap;
            padding: 3rem 2.5rem;
            background: rgba(255, 255, 255, 0.02);
            border: 0.5px solid rgba(255, 255, 255, 0.06);
            border-radius: 24px;
            margin-top: 4rem;
            backdrop-filter: blur(40px);
        }

        .security-trust-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.625rem;
            text-align: center;
        }

        .security-trust-icon {
            width: 36px;
            height: 36px;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 0.375rem;
        }

        .security-trust-label {
            font-size: 0.9375rem;
            font-weight: 600;
            color: #ffffff;
            letter-spacing: -0.015em;
        }

        .security-trust-value {
            font-size: 0.8125rem;
            color: rgba(255, 255, 255, 0.4);
            font-weight: 400;
            letter-spacing: -0.01em;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .security-container {
                padding: 0 1.5rem;
            }

            .security-grid {
                gap: 1rem;
            }
        }

        @media (max-width: 768px) {
            .security-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .security-card {
                padding: 2rem;
            }

            .security-trust {
                gap: 2.5rem;
                padding: 2.5rem 2rem;
            }

            .security-title {
                margin-bottom: 1rem;
            }

            .security-subtitle {
                margin-bottom: 3.5rem;
            }
        }

        @media (max-width: 480px) {
            .security-card {
                padding: 1.75rem;
            }

            .security-trust {
                flex-direction: column;
                gap: 2rem;
                padding: 2rem 1.5rem;
            }

            .security-container {
                padding: 0 1.25rem;
            }
        }
    </style>

    <!-- Background -->
    <div class="security-bg"></div>

    <!-- Content -->
    <div class="security-container">

        <!-- Header -->
        <div style="text-align: center; margin-bottom: 4rem;">
            <div class="security-badge">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                </svg>
                <span>Seguridad y Privacidad</span>
            </div>

            <h2 class="security-title">
                Tus datos protegidos<br>en todo momento
            </h2>

            <p class="security-subtitle">
                Implementamos las mejores prácticas de seguridad de la industria para garantizar que tu información y la de tus clientes permanezcan seguras y privadas.
            </p>
        </div>

        <!-- Security Features Grid -->
        <div class="security-grid">

            <!-- Feature 1: Encryption -->
            <div class="security-card">
                <div class="security-card-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                    </svg>
                </div>
                <h3 class="security-card-title">Encriptación AES-256</h3>
                <p class="security-card-description">
                    Todos tus datos sensibles están protegidos con encriptación militar de grado AES-256-CBC, el mismo estándar utilizado por bancos y gobiernos.
                </p>
            </div>

            <!-- Feature 2: Authentication -->
            <div class="security-card">
                <div class="security-card-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/>
                    </svg>
                </div>
                <h3 class="security-card-title">Autenticación Multi-Factor</h3>
                <p class="security-card-description">
                    Protección adicional con autenticación de dos factores (2FA), verificación de email obligatoria y códigos de recuperación seguros.
                </p>
            </div>

            <!-- Feature 3: Validation -->
            <div class="security-card">
                <div class="security-card-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                    </svg>
                </div>
                <h3 class="security-card-title">Validación Triple Capa</h3>
                <p class="security-card-description">
                    Los códigos de invitación utilizan triple validación: fingerprint HMAC-SHA256 para búsqueda rápida, verificación bcrypt y control de expiración.
                </p>
            </div>

            <!-- Feature 4: Sessions -->
            <div class="security-card">
                <div class="security-card-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="security-card-title">Sesiones Seguras</h3>
                <p class="security-card-description">
                    Sesiones almacenadas en servidor con cookies HTTP-only y políticas SameSite para prevenir ataques CSRF y XSS.
                </p>
            </div>

            <!-- Feature 5: Hierarchy -->
            <div class="security-card">
                <div class="security-card-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                </div>
                <h3 class="security-card-title">Control de Acceso Jerárquico</h3>
                <p class="security-card-description">
                    Sistema de permisos granular con cuatro niveles de jerarquía para controlar exactamente quién puede acceder a qué información.
                </p>
            </div>

            <!-- Feature 6: Audit -->
            <div class="security-card">
                <div class="security-card-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                </div>
                <h3 class="security-card-title">Auditoría Completa</h3>
                <p class="security-card-description">
                    Registro detallado de todas las acciones críticas: activaciones, cambios de contraseña, eliminaciones con soft-delete y trazabilidad total.
                </p>
            </div>

        </div>

        <!-- Trust Indicators -->
        <div class="security-trust">
            <div class="security-trust-item">
                <svg class="security-trust-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
                </svg>
                <div class="security-trust-label">Limitación de Intentos</div>
                <div class="security-trust-value">5 por minuto</div>
            </div>

            <div class="security-trust-item">
                <svg class="security-trust-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"/>
                </svg>
                <div class="security-trust-label">Backup Automático</div>
                <div class="security-trust-value">Diario</div>
            </div>

            <div class="security-trust-item">
                <svg class="security-trust-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                </svg>
                <div class="security-trust-label">Cumplimiento</div>
                <div class="security-trust-value">GDPR Ready</div>
            </div>

            <div class="security-trust-item">
                <svg class="security-trust-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                </svg>
                <div class="security-trust-label">Actualizaciones</div>
                <div class="security-trust-value">Continuas</div>
            </div>
        </div>

    </div>
</section>
