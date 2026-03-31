<?php

namespace App\Mail;

use App\Models\Commande;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class CommandePrete extends Mailable
{
    public function __construct(public Commande $commande) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Votre commande #' . $this->commande->id . ' est prête !');
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.commande-prete',
            with: ['commande' => $this->commande]
        );
    }
}