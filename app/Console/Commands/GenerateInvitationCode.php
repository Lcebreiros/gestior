<?php

namespace App\Console\Commands;

use App\Mail\InvitationCodeMail;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GenerateInvitationCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invitation:generate
                            {--type=company : Tipo de invitación (company, admin, user)}
                            {--level=premium : Nivel de suscripción (basic, premium, enterprise)}
                            {--expires=30 : Días hasta que expire (0 = sin expiración)}
                            {--users= : Máximo de usuarios (solo para company)}
                            {--notes= : Notas internas}
                            {--email= : Enviar código por email a esta dirección}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera un nuevo código de invitación';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');
        $level = $this->option('level');
        $expiresDays = (int) $this->option('expires');
        $maxUsers = $this->option('users');
        $notes = $this->option('notes');
        $email = $this->option('email');

        // Validar tipo
        if (!in_array($type, Invitation::getValidTypes())) {
            $this->error('Tipo de invitación inválido. Usa: company, admin o user');
            return 1;
        }

        // Validar nivel
        if (!in_array($level, Invitation::getValidSubscriptionLevels())) {
            $this->error('Nivel de suscripción inválido. Usa: basic, premium o enterprise');
            return 1;
        }

        // Buscar usuario master (hierarchy_level = -1)
        $masterUser = User::where('hierarchy_level', User::HIERARCHY_MASTER)->first();

        if (!$masterUser) {
            $this->error('No se encontró un usuario master en el sistema');
            $this->info('Crea un usuario master primero o usa el ID 1 por defecto');

            // Crear código de todos modos con created_by = 1
            $createdBy = 1;
        } else {
            $createdBy = $masterUser->id;
        }

        // Generar código único
        $code = Str::upper(Str::random(32));

        // Calcular expiración
        $expiresAt = $expiresDays > 0 ? Carbon::now()->addDays($expiresDays) : null;

        // Crear invitación
        try {
            $invitation = Invitation::create([
                'created_by' => $createdBy,
                'invitation_type' => $type,
                'subscription_level' => $level,
                'key_hash' => Hash::make($code),
                'key_fingerprint' => hash_hmac('sha256', $code, config('app.key')),
                'key_plain' => $code,
                'status' => Invitation::STATUS_PENDING,
                'expires_at' => $expiresAt,
                'max_users' => $maxUsers,
                'notes' => $notes,
            ]);

            // Mostrar información
            $this->info('✓ Código de invitación generado exitosamente');
            $this->newLine();

            $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            $this->line('  CÓDIGO DE INVITACIÓN');
            $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            $this->newLine();

            $this->line("  <fg=bright-green>$code</>");
            $this->newLine();

            $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            $this->line('  DETALLES');
            $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            $this->newLine();

            $this->line("  ID:               {$invitation->id}");
            $this->line("  Tipo:             {$invitation->type_label}");
            $this->line("  Suscripción:      " . ucfirst($level));

            if ($expiresAt) {
                $this->line("  Expira:           {$expiresAt->format('d/m/Y H:i')} ({$invitation->getDaysToExpire()} días)");
            } else {
                $this->line("  Expira:           Nunca");
            }

            if ($maxUsers) {
                $this->line("  Máx. usuarios:    {$maxUsers}");
            }

            if ($notes) {
                $this->line("  Notas:            {$notes}");
            }

            $this->newLine();
            $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            $this->newLine();

            $this->warn('⚠ IMPORTANTE: Este código solo se mostrará una vez.');
            $this->warn('  Guárdalo en un lugar seguro antes de continuar.');
            $this->newLine();

            // Enviar email si se especificó
            if ($email) {
                try {
                    Mail::to($email)->send(new InvitationCodeMail($code, $invitation, $email));
                    $this->info("✓ Email enviado exitosamente a: {$email}");
                    $this->newLine();
                } catch (\Exception $e) {
                    $this->error("✗ Error al enviar email: {$e->getMessage()}");
                    $this->newLine();
                }
            }

            return 0;

        } catch (\Exception $e) {
            $this->error('Error al crear la invitación: ' . $e->getMessage());
            return 1;
        }
    }
}
