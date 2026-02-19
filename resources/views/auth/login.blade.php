<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Iniciar sesión - {{ config('app.name', 'Gestior') }}</title>

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
            .login-bg {
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
            .login-container {
                width: 100%;
                max-width: 440px;
                padding: 2rem;
                position: relative;
                z-index: 10;
            }

            /* CARD DE LOGIN */
            .login-card {
                background: rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 1.5rem;
                padding: 2.5rem 2rem;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                transition: all 0.3s ease;
            }

            .login-card:hover {
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
            .login-header {
                text-align: center;
                margin-bottom: 2rem;
            }

            .login-title {
                font-size: 1.875rem;
                font-weight: 700;
                background: linear-gradient(135deg, #a78bfa, #8b5cf6);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                margin-bottom: 0.5rem;
            }

            .login-subtitle {
                color: #9ca3af;
                font-size: 0.875rem;
            }

            /* FORMULARIOS - ESTILOS COMPATIBLES CON JETSTREAM */
            .form-group {
                margin-bottom: 1.5rem;
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

            /* CHECKBOX - OPTIMIZADO */
            .checkbox-container {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                cursor: pointer;
            }

            .custom-checkbox {
                width: 1rem;
                height: 1rem;
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 0.25rem;
                position: relative;
                transition: all 0.2s ease;
                display: flex;
                align-items: center;
                justify-content: center;
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
            }

            /* BOTONES - MISMOS ESTILOS DEL WELCOME */
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

            /* LINKS */
            .link {
                color: #a78bfa;
                text-decoration: none;
                font-size: 0.875rem;
                transition: color 0.2s ease;
            }

            .link:hover {
                color: #c4b5fd;
            }

            /* ERRORES Y ESTADOS */
            .error-banner {
                display: flex;
                align-items: flex-start;
                gap: 0.75rem;
                background: rgba(239, 68, 68, 0.08);
                border: 1px solid rgba(239, 68, 68, 0.3);
                border-radius: 0.75rem;
                padding: 0.875rem 1rem;
                margin-bottom: 1.5rem;
                animation: shakeX 0.4s ease;
            }

            @keyframes shakeX {
                0%, 100% { transform: translateX(0); }
                20%       { transform: translateX(-6px); }
                40%       { transform: translateX(6px); }
                60%       { transform: translateX(-4px); }
                80%       { transform: translateX(4px); }
            }

            .error-banner-icon {
                flex-shrink: 0;
                width: 1.125rem;
                height: 1.125rem;
                color: #f87171;
                margin-top: 1px;
            }

            .error-banner-text {
                color: #fca5a5;
                font-size: 0.875rem;
                line-height: 1.5;
            }

            .field-error {
                display: flex;
                align-items: center;
                gap: 0.375rem;
                color: #f87171;
                font-size: 0.75rem;
                margin-top: 0.375rem;
            }

            .form-input.input-error {
                border-color: rgba(239, 68, 68, 0.5);
                box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.08);
            }

            .form-input.input-error:focus {
                border-color: rgba(239, 68, 68, 0.6);
                box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12);
            }

            .status-message {
                background: rgba(34, 197, 94, 0.1);
                border: 1px solid rgba(34, 197, 94, 0.2);
                border-radius: 0.75rem;
                padding: 1rem;
                margin-bottom: 1.5rem;
                color: #86efac;
                font-size: 0.875rem;
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
                .login-container {
                    padding: 1rem;
                }

                .login-card {
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
            .login-card {
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
        <div class="login-bg">
            <div class="particles" id="particles"></div>
        </div>

        <!-- Contenido principal -->
        <div class="login-container">
            <!-- Logo optimizado -->
            <div class="logo-container">
                <a href="/" class="flex items-center gap-2 no-underline">
                    <!-- Logo reemplazado por gestior.png -->
                    <img src="images/gestior.png" alt="Gestior" class="logo-image">
                </a>
            </div>

            <div class="login-card">
                <!-- Header -->
                <div class="login-header">
                    <h1 class="login-title">Iniciar sesión</h1>
                    <p class="login-subtitle">Accede a tu cuenta para continuar</p>
                </div>

                <!-- Banner de error de credenciales -->
                @if ($errors->has('email'))
                    @php
                        $msg = $errors->first('email');
                        // Normalizar mensajes de Fortify/Laravel a español
                        $esMsg = match(true) {
                            str_contains($msg, 'credentials') || str_contains($msg, 'match') =>
                                'El email o la contraseña son incorrectos. Verificá tus datos e intentá de nuevo.',
                            str_contains($msg, 'password') =>
                                'La contraseña ingresada es incorrecta.',
                            str_contains($msg, 'Too many') || str_contains($msg, 'throttle') =>
                                'Demasiados intentos fallidos. Esperá unos segundos antes de intentar de nuevo.',
                            default => $msg,
                        };
                    @endphp
                    <div class="error-banner">
                        <svg class="error-banner-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span class="error-banner-text">{{ $esMsg }}</span>
                    </div>
                @endif

                <!-- Otros errores de validación (ej: campos requeridos) -->
                @if ($errors->any() && !$errors->has('email'))
                    <div class="error-banner">
                        <svg class="error-banner-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span class="error-banner-text">Revisá los campos marcados antes de continuar.</span>
                    </div>
                @endif

                <!-- Mensaje de estado (ej: logout, reset password) -->
                @if (session('status'))
                    <div class="status-message">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Formulario -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input
                            id="email"
                            class="form-input {{ $errors->has('email') ? 'input-error' : '' }}"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="tu@email.com"
                        />
                    </div>

                    <!-- Contraseña -->
                    <div class="form-group">
                        <label for="password" class="form-label">Contraseña</label>
                        <div style="position:relative;">
                            <input
                                id="password"
                                class="form-input {{ $errors->has('email') ? 'input-error' : '' }}"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                style="padding-right: 2.75rem;"
                            />
                            <!-- Toggle mostrar/ocultar contraseña -->
                            <button type="button" id="togglePassword"
                                    style="position:absolute; right:0.75rem; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#9ca3af; padding:0; line-height:0;"
                                    aria-label="Mostrar contraseña">
                                <svg id="eyeIcon" style="width:1.1rem;height:1.1rem;" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                </svg>
                                <svg id="eyeOffIcon" style="display:none;width:1.1rem;height:1.1rem;" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.449l1.514 1.515a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/>
                                    <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.064 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Recordarme y olvidé contraseña -->
                    <div class="flex items-center justify-between">
                        <label class="checkbox-container">
                            <input type="checkbox" id="remember_me" name="remember" class="hidden" />
                            <div class="custom-checkbox" id="customCheckbox"></div>
                            <span class="checkbox-label">Recordarme</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="link" href="{{ route('password.request') }}">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>

                    <!-- Botones -->
                    <div class="form-footer">
                        <a href="{{ route('register') }}" class="btn-glass">
                            Crear cuenta
                        </a>
                        <button type="submit" class="btn-primary">
                            Iniciar sesión
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

            // Checkbox personalizado optimizado
            document.addEventListener('DOMContentLoaded', function() {
                createParticles();

                const checkbox = document.getElementById('remember_me');
                const customCheckbox = document.getElementById('customCheckbox');
                const checkboxContainer = document.querySelector('.checkbox-container');

                if (checkbox && customCheckbox && checkboxContainer) {
                    // Sincronizar estado inicial
                    if (checkbox.checked) {
                        customCheckbox.classList.add('checked');
                    }

                    // Manejar clicks en el contenedor del checkbox
                    checkboxContainer.addEventListener('click', function(e) {
                        e.preventDefault();
                        checkbox.checked = !checkbox.checked;
                        customCheckbox.classList.toggle('checked', checkbox.checked);
                    });
                }

                // Toggle mostrar/ocultar contraseña
                const toggleBtn = document.getElementById('togglePassword');
                const pwdInput  = document.getElementById('password');
                const eyeIcon   = document.getElementById('eyeIcon');
                const eyeOff    = document.getElementById('eyeOffIcon');
                if (toggleBtn && pwdInput) {
                    toggleBtn.addEventListener('click', function() {
                        const show = pwdInput.type === 'password';
                        pwdInput.type = show ? 'text' : 'password';
                        eyeIcon.style.display = show ? 'none' : '';
                        eyeOff.style.display  = show ? ''     : 'none';
                        toggleBtn.setAttribute('aria-label', show ? 'Ocultar contraseña' : 'Mostrar contraseña');
                    });
                }
            });
        </script>
    </body>
</html>