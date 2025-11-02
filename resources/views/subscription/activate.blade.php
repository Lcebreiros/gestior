<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Activar suscripción - {{ config('app.name', 'Gestior') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #000000;
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .bg-gradient {
            background: radial-gradient(ellipse at top, rgba(124, 58, 237, 0.15) 0%, #000000 50%);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .container {
            width: 100%;
            max-width: 520px;
            position: relative;
            z-index: 10;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .logo {
            width: 100px;
            height: 100px;
            filter: drop-shadow(0 8px 24px rgba(124, 58, 237, 0.3));
        }

        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            padding: 2.5rem 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .title {
            font-size: 1.875rem;
            font-weight: 700;
            background: linear-gradient(135deg, #a78bfa, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: #9ca3af;
            font-size: 0.875rem;
        }

        .plan-info {
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.1), rgba(109, 40, 217, 0.05));
            border: 1px solid rgba(124, 58, 237, 0.2);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .plan-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: #a78bfa;
            margin-bottom: 0.5rem;
        }

        .plan-features {
            list-style: none;
            margin-top: 1rem;
        }

        .plan-features li {
            padding: 0.5rem 0;
            color: #d1d5db;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .check-icon {
            color: #8b5cf6;
            flex-shrink: 0;
        }

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
            padding: 0.875rem 1rem;
            color: white;
            font-size: 1rem;
            font-family: 'Courier New', monospace;
            letter-spacing: 0.05em;
            transition: all 0.2s ease;
        }

        .form-input::placeholder {
            color: #9ca3af;
            font-family: 'Inter', sans-serif;
            letter-spacing: normal;
        }

        .form-input:focus {
            outline: none;
            border-color: rgba(167, 139, 250, 0.4);
            box-shadow: 0 0 0 3px rgba(167, 139, 250, 0.1);
            background: rgba(255, 255, 255, 0.08);
        }

        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #fca5a5;
            font-size: 0.875rem;
        }

        .success-message {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.2);
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #86efac;
            font-size: 0.875rem;
        }

        .submit-button {
            width: 100%;
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
            color: white;
            border: none;
            border-radius: 0.75rem;
            padding: 0.875rem 1.5rem;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(124, 58, 237, 0.3);
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(124, 58, 237, 0.4);
        }

        .submit-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: #a78bfa;
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: #c4b5fd;
        }

        .help-text {
            text-align: center;
            color: #9ca3af;
            font-size: 0.875rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        @media (max-width: 640px) {
            .card {
                padding: 2rem 1.5rem;
            }

            .logo {
                width: 80px;
                height: 80px;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
</head>
<body>
    <div class="bg-gradient"></div>

    <div class="container">
        <div class="logo-container">
            <img src="/images/gestior.png" alt="Gestior" class="logo">
        </div>

        <div class="card">
            <div class="header">
                <h1 class="title">Activar suscripción</h1>
                <p class="subtitle">Ingresa tu código de invitación para activar tu plan</p>
            </div>

            @if ($planInfo)
                <div class="plan-info">
                    <div class="plan-name">Plan {{ $planInfo['name'] }}</div>
                    <ul class="plan-features">
                        @foreach ($planInfo['features'] as $feature)
                            <li>
                                <svg class="check-icon" width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($errors->any())
                <div class="error-message">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div class="info-message" style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 0.75rem; padding: 1rem; margin-bottom: 1.5rem; color: #60a5fa; font-size: 0.875rem; display: flex; align-items: center; gap: 0.75rem;">
                    <svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('subscription.activate.submit') }}">
                @csrf

                <input type="hidden" name="plan" value="{{ $subscriptionLevel }}">

                <div class="form-group">
                    <label for="invitation_code" class="form-label">Código de invitación</label>
                    <input
                        type="text"
                        id="invitation_code"
                        name="invitation_code"
                        class="form-input"
                        placeholder="XXXX-XXXX-XXXX-XXXX"
                        value="{{ old('invitation_code') }}"
                        required
                        autofocus
                    >
                </div>

                <button type="submit" class="submit-button">
                    Activar plan
                </button>

                <a href="{{ route('subscription.plans') }}" class="back-link">
                    ← Cambiar de plan
                </a>

                <div class="help-text">
                    ¿No tienes un código? Contacta con el administrador
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-formatear el código mientras se escribe
        const codeInput = document.getElementById('invitation_code');

        codeInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();

            // Limitar a 32 caracteres
            if (value.length > 32) {
                value = value.substring(0, 32);
            }

            e.target.value = value;
        });
    </script>
</body>
</html>
