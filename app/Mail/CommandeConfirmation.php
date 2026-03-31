<?php

namespace App\Mail;

use App\Models\Commande;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class CommandeConfirmation extends Mailable
{
    public function __construct(public Commande $commande) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Confirmation de votre commande #' . $this->commande->id);
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.commande-confirmation');
    }
}