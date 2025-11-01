<?php

namespace App\Console\Commands;

use App\Models\Invitation;
use Illuminate\Console\Command;

class MigrateInvitationFingerprints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invitation:migrate-fingerprints
                            {--dry-run : Mostrar qué se haría sin hacer cambios}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera fingerprints para invitaciones que no los tienen';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $this->info('Buscando invitaciones sin fingerprint...');

        // Buscar invitaciones con key_plain pero sin fingerprint
        $invitations = Invitation::whereNotNull('key_plain')
            ->where(function ($query) {
                $query->whereNull('key_fingerprint')
                      ->orWhere('key_fingerprint', '');
            })
            ->get();

        if ($invitations->isEmpty()) {
            $this->info('✓ No hay invitaciones que necesiten migración');
            return 0;
        }

        $this->warn("Encontradas {$invitations->count()} invitaciones sin fingerprint");

        if ($dryRun) {
            $this->line('');
            $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            $this->line('  MODO DRY-RUN (no se harán cambios)');
            $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            $this->line('');
        }

        $bar = $this->output->createProgressBar($invitations->count());
        $bar->start();

        $updated = 0;
        $errors = 0;

        foreach ($invitations as $invitation) {
            try {
                $fingerprint = hash_hmac('sha256', $invitation->key_plain, config('app.key'));

                if ($dryRun) {
                    $this->newLine();
                    $this->line("  ID {$invitation->id}: Generaría fingerprint");
                } else {
                    $invitation->update(['key_fingerprint' => $fingerprint]);
                    $updated++;
                }

                $bar->advance();
            } catch (\Exception $e) {
                $errors++;
                $this->newLine();
                $this->error("  Error en invitación {$invitation->id}: {$e->getMessage()}");
            }
        }

        $bar->finish();
        $this->newLine(2);

        if ($dryRun) {
            $this->info("Se actualizarían {$invitations->count()} invitaciones");
            $this->line('Ejecuta sin --dry-run para aplicar los cambios');
        } else {
            $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            $this->info("✓ Migración completada");
            $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            $this->line('');
            $this->line("  Actualizadas:  {$updated}");

            if ($errors > 0) {
                $this->line("  Errores:       {$errors}");
            }

            $this->line('');
        }

        return $errors > 0 ? 1 : 0;
    }
}
