<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContatoLead extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $nome,
        public string $email,
        public string $telefone,
        public string $mensagem,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Novo contato pelo site - ' . $this->nome,
            to: [config('services.contato.to_email')],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.contato-lead',
        );
    }
}
