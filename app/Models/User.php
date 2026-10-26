<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Notificacion;
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password',
        'rol',
        'telefono',
        'biografia',
        'foto',
        'google_id',
        'estado',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification());
    }

    // Relaciones
    public function tutor()
{
    return $this->hasOne(Tutor::class);
}
public function notificaciones()
{
    return $this->hasMany(Notificacion::class)->orderBy('created_at', 'desc');
}

public function notificacionesNoLeidas()
{
    return $this->notificaciones()->where('leida', false);
}

}
