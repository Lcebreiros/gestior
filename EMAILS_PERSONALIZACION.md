# Personalización de Emails en Gestior

## Ubicación de los archivos de email

### 1. Clase Mailable (Lógica del email)
**Archivo:** `app/Mail/InvitationCodeMail.php`

Esta clase controla:
- El asunto del email
- Los datos que se pasan a la vista
- Los adjuntos (si los hay)

```php
public function envelope(): Envelope
{
    return new Envelope(
        subject: 'Tu código de invitación para Gestior', // ← Cambiar aquí el asunto
    );
}
```

### 2. Vista del email (Diseño HTML)
**Archivo:** `resources/views/emails/invitation-code.blade.php`

Esta es la plantilla HTML del email. Aquí puedes personalizar:

#### Colores principales
```css
/* Gradiente violeta principal */
background: linear-gradient(135deg, #7c3aed, #6d28d9);

/* Color del texto del código */
color: #a78bfa;

/* Color de fondo oscuro */
background-color: #000000;
```

#### Textos personalizables

**Saludo inicial:**
```html
<p class="greeting">
    ¡Hola! 👋<br><br>
    Has recibido un código de invitación para registrarte en <strong>Gestior</strong>,
    la plataforma de gestión empresarial que transformará tu negocio.
</p>
```

**Título del email:**
```html
<h1>Código de Invitación</h1>
```

**Instrucciones:**
```html
<div class="instructions">
    <h3>¿Cómo usar tu código?</h3>
    <ol>
        <li>Haz clic en el botón "Registrarme ahora"...</li>
        <!-- Personalizar pasos aquí -->
    </ol>
</div>
```

## Cómo usar el sistema de emails

### Generar código sin enviar email (solo mostrar en consola)
```bash
php artisan invitation:generate --type=company --level=premium --expires=30
```

### Generar código Y enviarlo por email
```bash
php artisan invitation:generate \
  --type=company \
  --level=premium \
  --expires=30 \
  --email=usuario@ejemplo.com \
  --notes="Bienvenido a nuestro equipo"
```

### Parámetros disponibles

| Parámetro | Descripción | Valores | Por defecto |
|-----------|-------------|---------|-------------|
| `--type` | Tipo de invitación | company, admin, user | company |
| `--level` | Nivel de suscripción | basic, premium, enterprise | premium |
| `--expires` | Días hasta expiración (0 = nunca) | número | 30 |
| `--users` | Máximo de usuarios permitidos | número | null |
| `--notes` | Notas para el destinatario | texto | null |
| `--email` | Email del destinatario | email válido | null |

## Ejemplos de personalización

### Cambiar el esquema de colores a azul

En `resources/views/emails/invitation-code.blade.php`:

```css
/* Cambiar de violeta a azul */
.header h1 {
    background: linear-gradient(135deg, #60a5fa, #3b82f6); /* Azul */
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.action-button {
    background: linear-gradient(135deg, #3b82f6, #2563eb); /* Azul */
}

.plan-badge {
    background: linear-gradient(135deg, #3b82f6, #2563eb); /* Azul */
}
```

### Cambiar el logo

Reemplaza la imagen en:
```html
<img src="{{ asset('images/gestior.png') }}" alt="Gestior" class="logo">
```

Por tu propio logo:
```html
<img src="{{ asset('images/mi-logo.png') }}" alt="Mi Empresa" class="logo">
```

### Agregar información adicional

Puedes agregar secciones personalizadas en la vista:

```html
<div style="margin: 30px 0; padding: 20px; background: rgba(255, 255, 255, 0.03); border-radius: 8px;">
    <h3 style="color: #a78bfa;">Beneficios de tu plan</h3>
    <ul style="color: #d1d5db;">
        <li>Acceso completo a todas las funciones</li>
        <li>Soporte prioritario 24/7</li>
        <li>Almacenamiento ilimitado</li>
    </ul>
</div>
```

### Cambiar el mensaje de bienvenida según el plan

Edita el archivo `InvitationCodeMail.php` para pasar datos adicionales:

```php
public function content(): Content
{
    $welcomeMessage = match($this->invitation->subscription_level) {
        'basic' => '¡Comienza tu viaje con Gestior!',
        'premium' => '¡Bienvenido a la experiencia Premium!',
        'enterprise' => '¡Prepárate para la excelencia empresarial!',
        default => '¡Bienvenido a Gestior!',
    };

    return new Content(
        view: 'emails.invitation-code',
        with: ['welcomeMessage' => $welcomeMessage]
    );
}
```

Y úsalo en la vista:

```html
<p class="greeting">
    {{ $welcomeMessage ?? '¡Bienvenido!' }}<br><br>
    Has recibido un código de invitación...
</p>
```

## Configuración del servidor de correo

Asegúrate de configurar tu servidor SMTP en el archivo `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-contraseña-de-aplicacion
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@gestior.com
MAIL_FROM_NAME="Gestior"
```

## Probar el email

### Opción 1: Usar Mailtrap (desarrollo)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu-username-mailtrap
MAIL_PASSWORD=tu-password-mailtrap
MAIL_ENCRYPTION=tls
```

### Opción 2: Usar el comando con tu email de prueba
```bash
php artisan invitation:generate --email=tu-email@ejemplo.com
```

## Archivos relacionados

- `/app/Mail/InvitationCodeMail.php` - Clase del email
- `/resources/views/emails/invitation-code.blade.php` - Plantilla HTML
- `/app/Console/Commands/GenerateInvitationCode.php` - Comando para generar códigos
- `/app/Services/SubscriptionService.php` - Lógica de suscripciones

## Soporte

Si necesitas ayuda adicional para personalizar los emails, consulta la documentación de Laravel sobre [Mail](https://laravel.com/docs/mail).
