# Guía de Personalización de Emails de Verificación

## 📧 Email de Verificación Personalizado

He personalizado completamente el email de verificación de Laravel Jetstream con el tema oscuro y violeta de Gestior.

## 📁 Archivos modificados/creados

### 1. **Estilos del Email** (Tema oscuro violeta)
**Archivo:** `resources/views/vendor/mail/html/themes/default.css`

Este archivo controla todo el diseño visual del email:

#### Colores principales:
- **Fondo:** Negro (#000000) con gradiente violeta
- **Texto:** Gris claro (#e5e7eb)
- **Enlaces:** Violeta claro (#a78bfa)
- **Botón principal:** Gradiente violeta (#7c3aed → #6d28d9)
- **Paneles:** Fondo violeta translúcido

### 2. **Notificación Personalizada**
**Archivo:** `app/Notifications/CustomVerifyEmail.php`

Esta clase controla el contenido del email de verificación:

```php
public function toMail($notifiable)
{
    $verificationUrl = $this->verificationUrl($notifiable);

    return (new MailMessage)
        ->subject('Verifica tu dirección de correo electrónico - ' . config('app.name'))
        ->greeting('¡Bienvenido a Gestior! 👋')
        ->line('Gracias por registrarte en **' . config('app.name') . '**, tu plataforma de gestión empresarial.')
        ->line('Para comenzar a usar todas las funcionalidades, necesitamos verificar tu dirección de correo electrónico.')
        ->action('Verificar correo electrónico', $verificationUrl)
        ->line('Este enlace de verificación expirará en ' . config('auth.verification.expire', 60) . ' minutos.')
        ->line('Si no creaste una cuenta, simplemente ignora este mensaje. No se requiere ninguna acción adicional.');
}
```

### 3. **Modelo User actualizado**
**Archivo:** `app/Models/User.php`

Agregado el método para usar la notificación personalizada:

```php
public function sendEmailVerificationNotification()
{
    $this->notify(new CustomVerifyEmail);
}
```

### 4. **Header con logo**
**Archivo:** `resources/views/vendor/mail/html/header.blade.php`

Configurado para mostrar el logo de Gestior automáticamente.

### 5. **Footer personalizado**
**Archivo:** `resources/views/vendor/mail/html/message.blade.php`

Footer con enlaces al sitio web y soporte.

## 🎨 Cómo personalizar

### Cambiar el texto del email

Edita el archivo `app/Notifications/CustomVerifyEmail.php`:

```php
return (new MailMessage)
    ->subject('Tu nuevo asunto aquí')
    ->greeting('¡Hola! 👋')  // ← Cambiar saludo
    ->line('Tu primer párrafo aquí')  // ← Cambiar texto
    ->action('Texto del botón', $verificationUrl)  // ← Cambiar botón
    ->line('Más texto...');
```

### Cambiar colores del email

Edita `resources/views/vendor/mail/html/themes/default.css`:

**Cambiar a esquema azul:**
```css
/* Botón principal */
.button-blue,
.button-primary {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    border-bottom: 8px solid #3b82f6;
    /* ... */
}

/* Enlaces */
a {
    color: #60a5fa;  /* azul claro */
}
```

**Cambiar a esquema verde:**
```css
.button-blue,
.button-primary {
    background: linear-gradient(135deg, #10b981, #059669);
    border-bottom: 8px solid #10b981;
    /* ... */
}

a {
    color: #34d399;  /* verde claro */
}
```

### Agregar más información al email

En `app/Notifications/CustomVerifyEmail.php`:

```php
return (new MailMessage)
    ->subject('Verifica tu correo')
    ->greeting('¡Bienvenido!')
    ->line('Primer párrafo')

    // Agregar panel informativo
    ->line('**Beneficios de tu cuenta:**')
    ->line('✓ Acceso completo a todas las funciones')
    ->line('✓ Soporte prioritario')
    ->line('✓ Almacenamiento ilimitado')

    ->action('Verificar correo', $verificationUrl)
    ->line('Gracias por elegir Gestior');
```

### Cambiar el tiempo de expiración

En el archivo `.env`:
```env
# Por defecto son 60 minutos
AUTH_VERIFICATION_EXPIRE=120  # 2 horas
```

O en `config/auth.php`:
```php
'verification' => [
    'expire' => 120, // minutos
],
```

### Agregar imágenes al email

En `app/Notifications/CustomVerifyEmail.php`:

```php
return (new MailMessage)
    ->subject('Verifica tu correo')
    ->greeting('¡Bienvenido!')

    // Agregar imagen
    ->line('![Banner](https://tudominio.com/images/banner.png)')

    ->action('Verificar', $verificationUrl);
```

## 🧪 Probar el email

### 1. Usar Mailtrap (desarrollo)

En `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu-username
MAIL_PASSWORD=tu-password
```

### 2. Usar el comando de prueba

Crea un comando de prueba:

```bash
php artisan make:command TestVerifyEmail
```

En `app/Console/Commands/TestVerifyEmail.php`:

```php
public function handle()
{
    $user = User::first();
    $user->sendEmailVerificationNotification();
    $this->info('Email enviado!');
}
```

Ejecutar:
```bash
php artisan test:verify-email
```

### 3. Registrar un usuario de prueba

Simplemente registra una cuenta nueva en tu aplicación y recibirás el email.

## 📊 Componentes del email

### Estructura del email de verificación:

```
┌─────────────────────────────────────┐
│         HEADER (Logo)               │
├─────────────────────────────────────┤
│                                     │
│  ¡Bienvenido a Gestior! 👋          │
│                                     │
│  Gracias por registrarte...         │
│                                     │
│  ┌─────────────────────────────┐   │
│  │ Verificar correo electrónico│   │  ← Botón violeta
│  └─────────────────────────────┘   │
│                                     │
│  Este enlace expira en 60 min...    │
│                                     │
├─────────────────────────────────────┤
│  © 2025 Gestior                     │
│  Visitar sitio | Soporte            │
└─────────────────────────────────────┘
```

## 🎯 Elementos personalizables

### En CustomVerifyEmail.php:
- ✅ Asunto del email
- ✅ Saludo inicial
- ✅ Párrafos de texto
- ✅ Texto del botón
- ✅ Tiempo de expiración
- ✅ Mensaje final

### En default.css:
- ✅ Colores de fondo
- ✅ Colores de texto
- ✅ Estilo del botón
- ✅ Bordes y sombras
- ✅ Tipografía

### En header.blade.php:
- ✅ Logo
- ✅ Tamaño del logo
- ✅ Efectos del logo

### En message.blade.php:
- ✅ Footer
- ✅ Enlaces del footer
- ✅ Copyright

## 🚀 Ejemplos adicionales

### Email más formal

```php
return (new MailMessage)
    ->subject('Verificación de cuenta - Gestior')
    ->greeting('Estimado/a usuario/a,')
    ->line('Le damos la bienvenida a Gestior.')
    ->line('Para completar el registro de su cuenta, necesitamos verificar su dirección de correo electrónico.')
    ->action('Verificar cuenta', $verificationUrl)
    ->line('Atentamente,')
    ->line('El equipo de Gestior');
```

### Email con urgencia

```php
return (new MailMessage)
    ->subject('⚡ Acción requerida: Verifica tu email')
    ->greeting('¡Hola! 🎉')
    ->line('Tu cuenta está casi lista. Solo falta un paso:')
    ->action('✓ Verificar ahora', $verificationUrl)
    ->line('**Importante:** Este enlace expira pronto.')
    ->line('¿Problemas? Contáctanos en soporte@gestior.com');
```

## 📝 Notas importantes

1. Los cambios en los estilos CSS afectan a TODOS los emails del sistema
2. Para emails específicos, crea una nueva clase Mailable
3. Siempre prueba en diferentes clientes de correo (Gmail, Outlook, etc.)
4. Los gradientes pueden no funcionar en todos los clientes de correo
5. Usa colores sólidos como fallback

## 🔗 Recursos útiles

- [Documentación de Laravel Mail](https://laravel.com/docs/mail)
- [Documentación de Notifications](https://laravel.com/docs/notifications)
- [Mailtrap para pruebas](https://mailtrap.io)
- [Can I Email (compatibilidad CSS)](https://www.caniemail.com)
