<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return (new MailMessage)
            ->subject('Restablece tu contraseña - TutorMatch')
            ->view('emails.auth.password-reset', [
                'nombre' => $notifiable->nombre,
                'url' => $this->resetUrl($notifiable),
                'minutos' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire'),
            ]);
    }
}
