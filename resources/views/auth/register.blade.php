<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Registrarse - {{ config('app.name', 'Gestior') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

        <style>
            :root {
                --violet-700: #7e22ce;
                --violet-800: #6b21a8;
                --violet-900: #581c87;
                --violet-950: #2a0b47;
            }

            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            body {
                font-family: 'Inter', sans-serif;
                overflow-x: hidden;
                background: #000000;
                color: white;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* FONDO CON DEGRADADO Y PARTÍCULAS */
            .register-bg {
                background: radial-gradient(ellipse at top, rgba(124, 58, 237, 0.15) 0%, #000000 50%);
                min-height: 100vh;
                width: 100%;
                position: fixed;
                top: 0;
                left: 0;
                z-index: -1;
            }

            /* PARTÍCULAS DE FONDO ANIMADAS */
            .particles {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                overflow: hidden;
                pointer-events: none;
                z-index: 0;
            }

            .particle {
                position: absolute;
                background: rgba(167, 139, 250, 0.3);
                border-radius: 50%;
                animation: float 20s infinite;
            }

            @keyframes float {
                0%, 100% {
                    transform: translate(0, 0) scale(1);
                    opacity: 0.3;
                }
                50% {
                    transform: translate(50px, -50px) scale(1.2);
                    opacity: 0.6;
                }
            }

            /* CONTENEDOR PRINCIPAL */
            .register-container {
                width: 100%;
                max-width: 440px;
                padding: 2rem;
                position: relative;
                z-index: 10;
            }

            /* CARD DE REGISTRO */
            .register-card {
                background: rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 1.5rem;
                padding: 2.5rem 2rem;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                transition: all 0.3s ease;
            }

            .register-card:hover {
                box-shadow: 0 25px 80px rgba(0, 0, 0, 0.4);
                border-color: rgba(255, 255, 255, 0.15);
            }

            /* LOGO - FUERA DEL CARD */
            .logo-container {
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 2rem;
            }

            .logo-image {
                width: 120px;
                height: 120px;
                object-fit: contain;
                display: block;
                filter: drop-shadow(0 8px 24px rgba(124, 58, 237, 0.3));
                transition: all 0.3s ease;
            }

            .logo-image:hover {
                transform: scale(1.05);
                filter: drop-shadow(0 12px 32px rgba(124, 58, 237, 0.4));
            }

            /* HEADER */
            .register-header {
                text-align: center;
                margin-bottom: 2rem;
            }

            .register-title {
                font-size: 1.875rem;
                font-weight: 700;
                background: linear-gradient(135deg, #a78bfa, #8b5cf6);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                margin-bottom: 0.5rem;
            }

            .register-subtitle {
                color: #9ca3af;
                font-size: 0.875rem;
            }

            /* FORMULARIOS */
            .form-group {
                margin-bottom: 1.25rem;
            }

            .form-label {
                display: block;
                font-size: 0.875rem;
                font-weight: 500;
                color: #e5e7eb;
                margin-bottom: 0.5rem;
            }

            .form-input {
                width: 100%;
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 0.75rem;
                padding: 0.75rem 1rem;
                color: white;
                font-size: 0.875rem;
                transition: all 0.2s ease;
            }

            .form-input::placeholder {
                color: #9ca3af;
            }

            .form-input:focus {
                outline: none;
                border-color: rgba(167, 139, 250, 0.4);
                box-shadow: 0 0 0 3px rgba(167, 139, 250, 0.1);
                background: rgba(255, 255, 255, 0.08);
            }

            /* CHECKBOX */
            .checkbox-container {
                display: flex;
                align-items: flex-start;
                gap: 0.75rem;
                cursor: pointer;
                margin-bottom: 1.5rem;
            }

            .custom-checkbox {
                width: 1rem;
                height: 1rem;
                min-width: 1rem;
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 0.25rem;
                position: relative;
                transition: all 0.2s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-top: 0.125rem;
            }

            .custom-checkbox.checked {
                background: linear-gradient(135deg, #8b5cf6, #7c3aed);
                border-color: #8b5cf6;
            }

            .custom-checkbox.checked::after {
                content: '✓';
                color: white;
                font-size: 0.75rem;
                font-weight: bold;
            }

            .checkbox-label {
                font-size: 0.875rem;
                color: #d1d5db;
                line-height: 1.4;
            }

            .checkbox-label a {
                color: #a78bfa;
                text-decoration: underline;
                transition: color 0.2s ease;
            }

            .checkbox-label a:hover {
                color: #c4b5fd;
            }

            /* BOTONES */
            .btn-primary {
                background: linear-gradient(135deg, #7c3aed, #6d28d9);
                color: white;
                border: none;
                border-radius: 0.75rem;
                padding: 0.75rem 1.5rem;
                font-weight: 600;
                font-size: 0.875rem;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 4px 16px rgba(124, 58, 237, 0.3);
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 24px rgba(124, 58, 237, 0.4);
            }

            .btn-glass {
                background: rgba(255, 255, 255, 0.08);
                color: white;
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 0.75rem;
                padding: 0.75rem 1.5rem;
                font-weight: 600;
                font-size: 0.875rem;
                cursor: pointer;
                transition: all 0.3s ease;
                text-decoration: none;
                text-align: center;
                display: inline-block;
            }

            .btn-glass:hover {
                background: rgba(255, 255, 255, 0.15);
                border-color: rgba(255, 255, 255, 0.2);
            }

            /* ERRORES Y ESTADOS */
            .validation-errors {
                background: rgba(239, 68, 68, 0.1);
                border: 1px solid rgba(239, 68, 68, 0.2);
                border-radius: 0.75rem;
                padding: 1rem;
                margin-bottom: 1.5rem;
            }

            .validation-errors ul {
                list-style: none;
                color: #fca5a5;
                font-size: 0.875rem;
            }

            .validation-errors ul li {
                margin-bottom: 0.25rem;
            }

            .validation-errors ul li:last-child {
                margin-bottom: 0;
            }

            /* FOOTER DEL FORM */
            .form-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                margin-top: 1.5rem;
            }

            @media (max-width: 640px) {
                .register-container {
                    padding: 1rem;
                }

                .register-card {
                    padding: 2rem 1.5rem;
                }

                .logo-image {
                    width: 100px;
                    height: 100px;
                }

                .logo-container {
                    margin-bottom: 1.5rem;
                }

                .form-footer {
                    flex-direction: column;
                    gap: 1rem;
                }

                .btn-glass, .btn-primary {
                    width: 100%;
                }
            }

            /* ANIMACIÓN DE ENTRADA */
            .register-card {
                opacity: 0;
                transform: translateY(20px);
                animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }

            @keyframes slideUp {
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    </head>
    <body>
        <!-- Fondo con partículas -->
        <div class="register-bg">
            <div class="particles" id="particles"></div>
        </div>

        <!-- Contenido principal -->
        <div class="register-container">
            <!-- Logo -->
            <div class="logo-container">
                <a href="/" class="flex items-center gap-2 no-underline">
                    <img src="images/gestior.png" alt="Gestior" class="logo-image">
                </a>
            </div>

            <div class="register-card">
                <!-- Header -->
                <div class="register-header">
                    <h1 class="register-title">Crear cuenta</h1>
                    <p class="register-subtitle">Comienza hoy y gestiona tu negocio</p>
                </div>

                <!-- Errores de validación -->
                @if ($errors->any())
                    <div class="validation-errors">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Formulario -->
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Nombre -->
                    <div class="form-group">
                        <label for="name" class="form-label">Nombre completo</label>
                        <input
                            id="name"
                            class="form-input"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Tu nombre completo"
                        />
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input
                            id="email"
                            class="form-input"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="username"
                            placeholder="tu@email.com"
                        />
                    </div>

                    <!-- Contraseña -->
                    <div class="form-group">
                        <label for="password" class="form-label">Contraseña</label>
                        <input
                            id="password"
                            class="form-input"
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="Mínimo 8 caracteres"
                        />
                    </div>

                    <!-- Confirmar contraseña -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                        <input
                            id="password_confirmation"
                            class="form-input"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Repite tu contraseña"
                        />
                    </div>

                    <!-- Términos y condiciones -->
                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <label class="checkbox-container">
                            <input type="checkbox" id="terms" name="terms" class="hidden" required />
                            <div class="custom-checkbox" id="customCheckbox"></div>
                            <span class="checkbox-label">
                                Acepto los
                                <a target="_blank" href="{{ route('terms.show') }}">Términos de Servicio</a>
                                y la
                                <a target="_blank" href="{{ route('policy.show') }}">Política de Privacidad</a>
                            </span>
                        </label>
                    @endif

                    <!-- Botones -->
                    <div class="form-footer">
                        <a href="{{ route('login') }}" class="btn-glass">
                            Ya tengo cuenta
                        </a>
                        <button type="submit" class="btn-primary">
                            Registrarse
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            // Crear partículas de fondo
            const particlesContainer = document.getElementById('particles');

            function createParticles() {
                for (let i = 0; i < 20; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'particle';
                    particle.style.width = Math.random() * 3 + 1 + 'px';
                    particle.style.height = particle.style.width;
                    particle.style.left = Math.random() * 100 + '%';
                    particle.style.top = Math.random() * 100 + '%';
                    particle.style.animationDelay = Math.random() * 20 + 's';
                    particle.style.animationDuration = (Math.random() * 10 + 15) + 's';
                    particlesContainer.appendChild(particle);
                }
            }

            // Checkbox personalizado
            document.addEventListener('DOMContentLoaded', function() {
                createParticles();

                const checkbox = document.getElementById('terms');
                const customCheckbox = document.getElementById('customCheckbox');
                const checkboxContainer = document.querySelector('.checkbox-container');

                if (checkbox && customCheckbox && checkboxContainer) {
                    // Sincronizar estado inicial
                    if (checkbox.checked) {
                        customCheckbox.classList.add('checked');
                    }

                    // Manejar clicks en el contenedor del checkbox
                    checkboxContainer.addEventListener('click', function(e) {
                        // No hacer nada si se hizo click en un enlace
                        if (e.target.tagName === 'A') {
                            return;
                        }
                        e.preventDefault();
                        checkbox.checked = !checkbox.checked;
                        customCheckbox.classList.toggle('checked', checkbox.checked);
                    });
                }
            });
        </script>
    </body>
</html>
