<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address; // Importa la clase Address

class UserResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user; // Para pasar el objeto de usuario al correo
    public $resetUrl; // Para pasar la URL de restablecimiento

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $resetUrl) // Recibe el usuario y la URL
    {
        $this->user = $user;
        $this->resetUrl = $resetUrl;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME')), // Opcional: Define remitente aquí
            subject: 'Restablecimiento de Contraseña para ' . $this->user->nombre, // Asunto del correo
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reset-password', // Define la vista que contendrá el cuerpo del correo
            with: [
                'userName' => $this->user->nombre,
                'resetLink' => $this->resetUrl,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        return []; // Puedes añadir archivos adjuntos si los necesitas
    }
}