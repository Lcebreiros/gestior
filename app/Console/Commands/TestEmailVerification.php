<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class TestEmailVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test-verify {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía un email de verificación de prueba a la dirección especificada';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        // Buscar o crear un usuario temporal
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Usuario de Prueba',
                'password' => bcrypt('password'),
                'hierarchy_level' => User::HIERARCHY_COMPANY,
            ]
        );

        // Enviar email de verificación
        $user->sendEmailVerificationNotification();

        $this->info("✓ Email de verificación enviado exitosamente a: {$email}");
        $this->newLine();
        $this->warn("Nota: Si el usuario ya existía, se usó ese registro.");

        return 0;
    }
}
