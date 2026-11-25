<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

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
    public function tutor(): HasOne
    {
        return $this->hasOne(Tutor::class);
    }

    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class)->orderBy('created_at', 'desc');
    }

    public function notificacionesNoLeidas()
    {
        return $this->notificaciones()->where('leida', false);
    }

    /**
     * Clases reservadas como estudiante.
     */
    public function clases(): HasMany
    {
        return $this->hasMany(Clase::class, 'estudiante_id');
    }

    /**
     * Pagos realizados (clases y suscripciones).
     */
    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    /**
     * Reseñas escritas como estudiante.
     */
    public function resenas(): HasMany
    {
        return $this->hasMany(Resena::class, 'estudiante_id');
    }
}
