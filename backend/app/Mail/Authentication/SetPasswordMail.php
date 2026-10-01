<?php

namespace App\Mail\Authentication;

use App\Models\Authentication\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $token,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Set your account password',
        );
    }

    public function content(): Content
    {
        $url = rtrim(config('app.frontend_url', config('app.url')), '/')
             . '/set-password?token=' . $this->token;

        return new Content(
            view: 'emails.authentication.set-password',
            with: [
                'user' => $this->user,
                'url'  => $url,
            ],
        );
    }
}