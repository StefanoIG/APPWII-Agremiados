<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class UpdateSecretariaPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:update-password {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar la contraseña de un usuario específico';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("Usuario con email {$email} no encontrado.");
            return;
        }

        $user->password = Hash::make($password);
        $user->save();

        $this->info("Contraseña actualizada exitosamente para {$user->name} ({$email})");
        $this->info("Nueva contraseña: {$password}");
    }
}
