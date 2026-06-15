<?php

namespace App\Mail;

use App\Models\Mensaje;
use App\Models\PlantillaCorreo;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmacionContacto extends Mailable
{
    use SerializesModels;

    public function __construct(
        public readonly Mensaje $mensaje,
        public readonly PlantillaCorreo $plantilla,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->plantilla->asunto,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.confirmacion_contacto',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
