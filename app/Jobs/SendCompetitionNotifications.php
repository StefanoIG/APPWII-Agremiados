<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\Competition;
use App\Models\User;
use App\Mail\CompetitionCreatedMail;

class SendCompetitionNotifications implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public $competition;

    /**
     * Create a new job instance.
     */
    public function __construct(Competition $competition)
    {
        $this->competition = $competition;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Obtener usuarios con suscripciones activas
        $usersWithActiveSubscriptions = User::whereHas('subscriptions', function ($query) {
            $query->where('status', 'active')
                  ->where('expires_at', '>', now());
        })->where('active', true)->get();

        // Enviar correo a cada usuario
        foreach ($usersWithActiveSubscriptions as $user) {
            Mail::to($user->email)->send(new CompetitionCreatedMail($this->competition, $user));
        }
    }
}
