<?php

namespace App\Mail;

use App\Models\MonthlyCut;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MonthlyCutCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $monthlyCut;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(MonthlyCut $monthlyCut, User $user)
    {
        $this->monthlyCut = $monthlyCut;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuevo Corte Mensual - ' . $this->monthlyCut->cut_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.monthly-cut-created',
            with: [
                'monthlyCut' => $this->monthlyCut,
                'user' => $this->user,
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
