# Sistema de Suscripciones con Códigos de Invitación

Este documento explica cómo funciona el sistema de suscripciones implementado en Gestior.

## Flujo del Usuario

### 1. Registro
- El usuario se registra en el sistema
- Se crea con `is_active = false` y sin `subscription_level`
- Debe verificar su email primero

### 2. Verificación de Email
- El usuario recibe un email de verificación en `notificaciones@gestior.com.ar`
- Debe verificar su email antes de continuar

### 3. Selección de Plan y Activación
- **Ruta:** `/planes`
- El usuario ve 3 planes disponibles:
  - **Básico**: Hasta 5 usuarios, 1 sucursal
  - **Premium**: Hasta 50 usuarios, 5 sucursales
  - **Enterprise**: Usuarios y sucursales ilimitados
- Al hacer clic en "Seleccionar", se abre un modal
- En el modal, el usuario ingresa su código de invitación
- Al validar el código:
  - Se asigna el `subscription_level` al usuario
  - Se marca la invitación como usada
  - Se activa el usuario (`is_active = true`)
  - Se asigna el `hierarchy_level` según el tipo de invitación
  - Se registra en el historial

### 4. Acceso al Dashboard
- Una vez activo, el usuario puede acceder al dashboard y todas las funcionalidades

## Arquitectura del Sistema

### Modelos

#### Invitation
- **Campos clave:**
  - `subscription_level`: basic, premium, enterprise
  - `invitation_type`: company, admin, user
  - `key_hash`: Código hasheado con bcrypt
  - `key_fingerprint`: HMAC-SHA256 para búsqueda rápida
  - `key_plain`: Código visible (se elimina al usar)
  - `status`: pending, used, revoked, expired

#### User
- **Campos clave:**
  - `subscription_level`: El plan seleccionado
  - `is_active`: true si tiene suscripción activa
  - `hierarchy_level`: -1 (master), 0 (company), 1 (admin), 2 (user)
  - `user_limit`: Límite de usuarios según el plan
  - `branch_limit`: Límite de sucursales según el plan

### Servicios

#### SubscriptionService
- `assignPlan()`: Asigna un plan a un usuario
- `validateInvitationCode()`: Valida un código de invitación
- `activateSubscription()`: Activa la suscripción con un código
- `hasActiveSubscription()`: Verifica si tiene suscripción activa
- `needsActivation()`: Verifica si necesita activar

### Middleware

#### EnsureActiveSubscription
Protege las rutas que requieren suscripción activa:
1. Verifica email verificado
2. Verifica plan seleccionado
3. Verifica suscripción activa

### Rutas

```php
// Públicas
GET  /                           → welcome
GET  /planes                     → planes públicos (con modal para autenticados)

// Autenticación (Jetstream)
GET  /register                   → registro
GET  /login                      → login
GET  /email/verify               → verificación de email

// Suscripción (auth + verified)
POST /subscription/activate      → procesar código y activar plan

// Dashboard (auth + verified + subscription)
GET  /dashboard                  → panel principal
GET  /user/profile              → perfil de usuario
```

## Creación de Códigos de Invitación

Para crear códigos de invitación, debes usar el modelo `Invitation`:

```php
use App\Models\Invitation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// Generar código único
$code = Str::upper(Str::random(32));

// Crear invitación
$invitation = Invitation::create([
    'created_by' => 1, // ID del usuario master
    'invitation_type' => Invitation::TYPE_COMPANY,
    'subscription_level' => 'premium',
    'key_hash' => Hash::make($code),
    'key_fingerprint' => hash_hmac('sha256', $code, config('app.key')),
    'key_plain' => $code, // Se mostrará solo una vez
    'status' => Invitation::STATUS_PENDING,
    'expires_at' => now()->addDays(30),
    'max_users' => 50,
    'notes' => 'Código para cliente XYZ',
]);

// Mostrar código al administrador (solo esta vez)
echo "Código de invitación: " . $code;
```

## Validación de Códigos

El sistema usa triple validación de seguridad:

1. **key_fingerprint**: HMAC-SHA256 para búsqueda rápida en BD (indexado)
2. **key_hash**: bcrypt para verificación final segura
3. **key_plain**: Solo visible al crear, luego se elimina al usar

### Proceso de Validación

```php
// 1. Generar fingerprint del código ingresado
$fingerprint = hash_hmac('sha256', $code, config('app.key'));

// 2. Buscar invitación por fingerprint (rápido)
$invitation = Invitation::where('key_fingerprint', $fingerprint)
    ->where('status', 'pending')
    ->first();

// 3. Verificar con bcrypt (seguro)
if (Hash::check($code, $invitation->key_hash)) {
    // Código válido
}
```

## Historial de Invitaciones

Cada vez que se usa un código, se registra en `invitations_history`:

```php
InvitationHistory::create([
    'invitation_id' => $invitation->id,
    'key' => substr($code, 0, 8) . '***',
    'email' => $user->email,
    'used_at' => now(),
    'used_by' => $user->id,
    'payload' => [
        'subscription_level' => $invitation->subscription_level,
        'invitation_type' => $invitation->invitation_type,
    ],
]);
```

## Configuración

### Email
Los emails de verificación y notificaciones se envían desde:
- `notificaciones@gestior.com.ar`

Configurado en `.env`:
```env
MAIL_FROM_ADDRESS="notificaciones@gestior.com.ar"
MAIL_FROM_NAME="${APP_NAME}"
```

### Redirecciones
Después del registro, el usuario es redirigido a `/planes` (configurado en `config/fortify.php`).

## Testing

Para probar el flujo completo:

1. Registrar un usuario nuevo
2. Verificar email
3. Ir a `/planes`
4. Hacer clic en "Seleccionar" en el plan Premium
5. Crear un código de invitación para Premium usando el comando artisan
6. Ingresar el código en el modal
7. Verificar acceso al dashboard

## Seguridad

- ✅ Códigos hasheados con bcrypt
- ✅ Búsqueda indexada con HMAC-SHA256
- ✅ Códigos de un solo uso
- ✅ Expiración de códigos
- ✅ Auditoría completa en historial
- ✅ Middleware de protección en rutas
- ✅ Validación de plan coincidente

## Próximas Mejoras

- [ ] Implementar generación de códigos desde el panel de admin
- [ ] Agregar límite de usos por código (para múltiples usuarios)
- [ ] Implementar renovación de suscripciones
- [ ] Agregar notificaciones de expiración
- [ ] Dashboard de gestión de invitaciones
