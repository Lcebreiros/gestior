# Integración con Rellenito-Alfajores

Este documento explica cómo hacer que los códigos de invitación generados desde el panel de **rellenito-alfajores** funcionen perfectamente en **Gestior**, ya que ambos proyectos comparten la misma base de datos.

## Estado Actual

✅ Ambos proyectos comparten la misma base de datos
✅ La tabla `invitations` es la misma
✅ El modelo `Invitation` tiene la misma estructura
✅ Los códigos se generan con `key_hash`, `key_fingerprint` y `key_plain`

## Diferencias a Resolver

### 1. Búsqueda de Códigos

**Problema**: El `InvitationService` de rellenito-alfajores no usa el `fingerprint` para buscar códigos de manera eficiente.

**Solución**: Actualizar el método `findAndValidateInvitation` en rellenito-alfajores.

#### Archivo a modificar:
`/home/leandro/rellenito-alfajores/app/Services/InvitationService.php`

#### Método actual (INEFICIENTE):
```php
public function findAndValidateInvitation(string $plainKey): ?Invitation
{
    // ❌ Esto obtiene TODAS las invitaciones pendientes
    $invitations = Invitation::where('status', Invitation::STATUS_PENDING)->get();

    foreach ($invitations as $invitation) {
        if ($this->validateKey($plainKey, $invitation)) {
            if ($invitation->expires_at && $invitation->expires_at->isPast()) {
                $invitation->update(['status' => Invitation::STATUS_EXPIRED]);
                return null;
            }
            return $invitation;
        }
    }

    return null;
}
```

#### Método mejorado (EFICIENTE):
```php
public function findAndValidateInvitation(string $plainKey): ?Invitation
{
    // ✅ Usar fingerprint para búsqueda indexada (mucho más rápido)
    $fingerprint = $this->computeFingerprint($plainKey);

    $invitation = Invitation::where('key_fingerprint', $fingerprint)
        ->where('status', Invitation::STATUS_PENDING)
        ->first();

    if (!$invitation) {
        return null;
    }

    // Verificar que no ha expirado
    if ($invitation->expires_at && $invitation->expires_at->isPast()) {
        $invitation->update(['status' => Invitation::STATUS_EXPIRED]);
        return null;
    }

    // Verificar el hash (seguridad final)
    if (!Hash::check($plainKey, $invitation->key_hash)) {
        return null;
    }

    return $invitation;
}
```

### 2. Compatibilidad de Planes

**Importante**: Los códigos generados desde rellenito-alfajores deben tener el `subscription_level` correcto:

- `basic` → Plan Básico
- `premium` → Plan Premium
- `enterprise` → Plan Enterprise

Estos valores deben coincidir exactamente (en minúsculas).

## Cómo Generar Códigos desde Rellenito-Alfajores

### Opción 1: Usando el Panel Web

Si ya tienes un panel para generar códigos en rellenito-alfajores, asegúrate de:

1. Seleccionar el `subscription_level` correcto: `basic`, `premium`, o `enterprise`
2. Seleccionar el `invitation_type` adecuado:
   - `company` → Para empresas (hierarchy_level = 0)
   - `admin` → Para administradores (hierarchy_level = 1)
   - `user` → Para usuarios (hierarchy_level = 2)

### Opción 2: Usando Código PHP

```php
use App\Services\InvitationService;

$invitationService = app(InvitationService::class);

$result = $invitationService->createInvitation(
    masterUserId: 1,
    invitationType: 'company',
    subscriptionLevel: 'premium', // ⚠️ Debe ser: basic, premium, o enterprise
    permissions: null,
    maxUsers: 50,
    expiresInHours: 720, // 30 días
    notes: 'Código para cliente XYZ'
);

// Mostrar código al administrador
$plainKey = $result['plain_key'];
echo "Código de invitación: {$plainKey}";
```

### Opción 3: Usando Comando Artisan desde Gestior

Desde el proyecto Gestior, puedes generar códigos directamente:

```bash
cd /home/leandro/gestior

# Generar código Premium
php artisan invitation:generate --level=premium --type=company --expires=30

# Generar código Basic
php artisan invitation:generate --level=basic --type=user --expires=7

# Generar código Enterprise sin expiración
php artisan invitation:generate --level=enterprise --type=company --expires=0
```

## Verificación de Compatibilidad

### Verificar que un código existe en la BD:

```sql
-- Desde MySQL
mysql -u root -p'lanterminO1' rellenito-alfajores

-- Ver códigos pendientes
SELECT
    id,
    invitation_type,
    subscription_level,
    key_plain,
    status,
    expires_at,
    created_at
FROM invitations
WHERE status = 'pending'
ORDER BY created_at DESC
LIMIT 10;
```

### Probar un código desde Gestior:

1. Ir a `http://localhost:8000/planes` (o la URL de Gestior)
2. Iniciar sesión con un usuario que no tenga suscripción activa
3. Click en "Seleccionar" en cualquier plan
4. Ingresar el código generado
5. Verificar que la activación funciona correctamente

## Flujo Completo de Trabajo

### Escenario 1: Admin genera código desde Rellenito, Usuario lo usa en Gestior

1. **Admin en Rellenito-Alfajores**:
   - Accede al panel de invitaciones
   - Genera un código para plan Premium
   - Obtiene el código: `ABCD-1234-EFGH`
   - Envía el código al cliente

2. **Usuario en Gestior**:
   - Se registra en Gestior
   - Verifica su email
   - Va a `/planes`
   - Click en "Seleccionar" en Premium
   - Ingresa el código `ABCD-1234-EFGH`
   - ✅ Suscripción activada

3. **Sistema**:
   - Marca la invitación como `used`
   - Registra en `invitations_history`
   - Activa el usuario (`is_active = true`)
   - Asigna límites según el plan

### Escenario 2: Admin genera código desde Gestior

1. **Admin en Gestior**:
   ```bash
   php artisan invitation:generate --level=premium --type=company
   ```
   - Obtiene el código
   - Lo envía al cliente

2. **Usuario**: Mismo flujo que el escenario 1

## Checklist de Compatibilidad

Antes de generar códigos, verifica que:

- [ ] El `subscription_level` es uno de: `basic`, `premium`, `enterprise`
- [ ] El `invitation_type` es uno de: `company`, `admin`, `user`
- [ ] El `status` es `pending`
- [ ] El campo `key_fingerprint` está generado correctamente
- [ ] El campo `key_hash` está hasheado con bcrypt
- [ ] El campo `key_plain` contiene el código (se borrará al usar)

## Solución de Problemas

### Error: "Código de invitación inválido o expirado"

**Causas posibles**:
1. El código ya fue usado (`status = 'used'`)
2. El código expiró (`expires_at` pasó)
3. El `key_fingerprint` no coincide
4. El `subscription_level` no es válido

**Verificar**:
```sql
SELECT
    id,
    status,
    subscription_level,
    expires_at,
    used_at,
    used_by
FROM invitations
WHERE key_plain LIKE 'ABCD%'; -- Primeros caracteres del código
```

### Error: "El código no corresponde al plan seleccionado"

**Causa**: El `subscription_level` de la invitación no coincide con el plan seleccionado.

**Solución**: Generar un nuevo código con el `subscription_level` correcto.

### Los códigos se generan pero no se encuentran

**Causa**: El método `findAndValidateInvitation` no está usando el `fingerprint`.

**Solución**: Aplicar la mejora descrita en la sección "Búsqueda de Códigos" arriba.

## Migración de Códigos Existentes

Si ya tienes códigos generados sin `key_fingerprint`:

```php
// Script de migración
use App\Models\Invitation;
use Illuminate\Support\Facades\Hash;

Invitation::whereNotNull('key_plain')
    ->where('status', 'pending')
    ->chunk(100, function ($invitations) {
        foreach ($invitations as $invitation) {
            $fingerprint = hash_hmac('sha256', $invitation->key_plain, config('app.key'));
            $invitation->update(['key_fingerprint' => $fingerprint]);
        }
    });
```

## Resumen

✅ **Los códigos son 100% compatibles** entre ambos proyectos
✅ **Comparten la misma base de datos**
✅ **Solo necesitas aplicar la mejora del método `findAndValidateInvitation`** en rellenito-alfajores
✅ **Puedes generar códigos desde cualquier proyecto** y usarlos en el otro

La clave es asegurarse de que:
1. El `subscription_level` sea válido (`basic`, `premium`, `enterprise`)
2. El `key_fingerprint` esté correctamente generado
3. El método de búsqueda use el fingerprint para eficiencia
