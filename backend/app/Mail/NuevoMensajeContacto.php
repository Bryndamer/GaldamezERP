<?php

namespace App\Mail;

use App\Models\Mensaje;
use App\Models\PlantillaCorreo;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NuevoMensajeContacto extends Mailable
{
    use SerializesModels;

    public function __construct(
        public readonly Mensaje $mensaje,
        public readonly PlantillaCorreo $plantilla,
    ) {}

    public function envelope(): Envelope
    {
        $asunto = str_replace(':nombre', $this->mensaje->nombre, $this->plantilla->asunto);

        return new Envelope(
            subject: $asunto,
            replyTo: [$this->mensaje->email],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.nuevo_mensaje',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
