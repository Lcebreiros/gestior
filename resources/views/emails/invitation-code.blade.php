<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Invitación - Gestior</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #000000;
            color: #ffffff;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.1) 0%, rgba(0, 0, 0, 0.95) 50%);
        }
        .header {
            text-align: center;
            padding: 40px 20px 20px;
        }
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, #a78bfa, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .content {
            padding: 20px 40px 40px;
        }
        .greeting {
            font-size: 18px;
            color: #e5e7eb;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .plan-info {
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.15), rgba(109, 40, 217, 0.1));
            border: 1px solid rgba(124, 58, 237, 0.3);
            border-radius: 12px;
            padding: 24px;
            margin: 30px 0;
            text-align: center;
        }
        .plan-badge {
            display: inline-block;
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 12px;
        }
        .plan-name {
            font-size: 22px;
            font-weight: 600;
            color: #a78bfa;
            margin: 10px 0;
        }
        .code-section {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(167, 139, 250, 0.3);
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .code-label {
            font-size: 14px;
            color: #9ca3af;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .code {
            font-family: 'Courier New', monospace;
            font-size: 24px;
            font-weight: 700;
            color: #a78bfa;
            background: rgba(167, 139, 250, 0.1);
            padding: 16px 24px;
            border-radius: 8px;
            letter-spacing: 2px;
            word-break: break-all;
            margin: 10px 0;
        }
        .code-hint {
            font-size: 13px;
            color: #6b7280;
            margin-top: 12px;
        }
        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
            color: white;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            box-shadow: 0 4px 16px rgba(124, 58, 237, 0.3);
        }
        .instructions {
            background: rgba(255, 255, 255, 0.03);
            border-left: 4px solid #7c3aed;
            padding: 20px;
            margin: 30px 0;
            border-radius: 6px;
        }
        .instructions h3 {
            margin-top: 0;
            color: #a78bfa;
            font-size: 16px;
        }
        .instructions ol {
            margin: 10px 0;
            padding-left: 20px;
            color: #d1d5db;
        }
        .instructions li {
            margin: 8px 0;
            line-height: 1.6;
        }
        .expiration-warning {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.3);
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
            color: #fbbf24;
            font-size: 14px;
            text-align: center;
        }
        .footer {
            text-align: center;
            padding: 30px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #6b7280;
            font-size: 13px;
        }
        .footer a {
            color: #8b5cf6;
            text-decoration: none;
        }
        .security-note {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
            color: #fca5a5;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="header">
            <img src="{{ asset('images/gestior.png') }}" alt="Gestior" class="logo">
            <h1>Código de Invitación</h1>
        </div>

        <div class="content">
            <p class="greeting">
                ¡Hola! 👋<br><br>
                Has recibido un código de invitación para registrarte en <strong>Gestior</strong>,
                la plataforma de gestión empresarial que transformará tu negocio.
            </p>

            <div class="plan-info">
                <div class="plan-badge">{{ $invitation->type_label }}</div>
                <div class="plan-name">
                    Plan {{ ucfirst($invitation->subscription_level) }}
                </div>
                @if($invitation->max_users)
                    <div style="color: #9ca3af; font-size: 14px; margin-top: 8px;">
                        Hasta {{ $invitation->max_users }} usuarios
                    </div>
                @endif
            </div>

            <div class="code-section">
                <div class="code-label">Tu Código de Invitación</div>
                <div class="code">{{ $code }}</div>
                <div class="code-hint">Copia este código tal como aparece</div>
            </div>

            @if($invitation->expires_at)
                <div class="expiration-warning">
                    ⏰ Este código expira el {{ $invitation->expires_at->format('d/m/Y') }}
                    ({{ $invitation->getDaysToExpire() }} días restantes)
                </div>
            @endif

            <div style="text-align: center;">
                <a href="{{ config('app.url') }}/register" class="action-button">
                    Registrarme ahora
                </a>
            </div>

            <div class="instructions">
                <h3>¿Cómo usar tu código?</h3>
                <ol>
                    <li>Haz clic en el botón "Registrarme ahora" o visita {{ config('app.url') }}</li>
                    <li>Completa el formulario de registro con tus datos</li>
                    <li>Selecciona el plan <strong>{{ ucfirst($invitation->subscription_level) }}</strong></li>
                    <li>Ingresa el código de invitación cuando se te solicite</li>
                    <li>¡Listo! Comienza a usar Gestior inmediatamente</li>
                </ol>
            </div>

            <div class="security-note">
                <strong>⚠️ Importante:</strong> Este código es de un solo uso y personal.
                No lo compartas con otras personas. Si no solicitaste esta invitación,
                puedes ignorar este correo.
            </div>

            @if($invitation->notes)
                <div style="margin: 30px 0; padding: 20px; background: rgba(255, 255, 255, 0.03); border-radius: 8px;">
                    <strong style="color: #a78bfa;">Nota del administrador:</strong><br>
                    <span style="color: #d1d5db;">{{ $invitation->notes }}</span>
                </div>
            @endif
        </div>

        <div class="footer">
            <p>
                ¿Necesitas ayuda? Visita nuestro <a href="{{ config('app.url') }}/contacto">centro de soporte</a>
            </p>
            <p style="margin-top: 20px;">
                © {{ date('Y') }} Gestior. Todos los derechos reservados.<br>
                <a href="{{ config('app.url') }}">{{ config('app.url') }}</a>
            </p>
        </div>
    </div>
</body>
</html>
