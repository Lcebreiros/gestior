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

            /* LOGO - OPTIMIZADO */
            .logo-container {
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 1.5rem; /* Reducido de 2rem */
            }

            .logo-image {
                width: 100px; /* Aumentado de 80px */
                height: 100px; /* Aumentado de 80px */
                object-fit: contain;
                display: block;
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
                    width: 90px;
                    height: 90px;
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
            <div class="login-card">
                <!-- Logo optimizado -->
                <div class="logo-container">
                    <a href="/" class="flex items-center gap-2 no-underline">
                        <!-- Logo reemplazado por gestior.png -->
                        <img src="images/gestior.png" alt="Gestior" class="logo-image">
                    </a>
                </div>

                <!-- Header -->
                <div class="login-header">
                    <h1 class="login-title">Iniciar sesión</h1>
                    <p class="login-subtitle">Accede a tu cuenta para continuar</p>
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

                <!-- Mensaje de estado -->
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
                            class="form-input" 
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
                        <input 
                            id="password" 
                            class="form-input" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            placeholder="••••••••"
                        />
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
                        // Evitar que se active dos veces si se hace clic directamente en el checkbox
                        if (e.target !== checkbox) {
                            checkbox.checked = !checkbox.checked;
                            customCheckbox.classList.toggle('checked', checkbox.checked);
                        }
                    });
                    
                    // Sincronizar cambios del checkbox real
                    checkbox.addEventListener('change', function() {
                        customCheckbox.classList.toggle('checked', this.checked);
                    });
                }
            });
        </script>
    </body>
</html>