<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Competition;
use App\Models\User;

class CompetitionCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $competition;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(Competition $competition, User $user)
    {
        $this->competition = $competition;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🏆 Nueva Competencia Disponible: ' . $this->competition->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.competition-created',
            with: [
                'competition' => $this->competition,
                'user' => $this->user,
                'competitionUrl' => route('competitions.show', $this->competition),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
