<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail
{
    public function toMail($notifiable)
    {
        $url = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $url);
        }

        return (new MailMessage)
            ->subject('Confirma tu correo - TutorMatch')
            ->view('emails.auth.verify-email', [
                'nombre' => $notifiable->nombre,
                'url' => $url,
            ]);
    }
}
