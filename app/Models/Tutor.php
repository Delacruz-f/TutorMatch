<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tutor extends Model
{
    protected $table = 'tutores';

    protected $fillable = [
        'user_id', 'universidad', 'carrera', 'dni_numero', 'dni_foto',
        'cv_archivo', 'precio_hora', 'aprobacion', 'motivo_rechazo',
        'plan', 'plan_vence', 'calificacion_promedio', 'total_resenas'
    ];

    protected $casts = [
        'precio_hora' => 'decimal:2',
        'calificacion_promedio' => 'decimal:2',
        'plan_vence' => 'date',
    ];

    // Relaciones
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function materias(): BelongsToMany
    {
        return $this->belongsToMany(Materia::class, 'materia_tutor', 'tutor_id', 'materia_id');
    }

    public function clases(): HasMany
    {
        return $this->hasMany(Clase::class);
    }

    public function retiros(): HasMany
    {
        return $this->hasMany(Retiro::class);
    }

    public function resenas(): HasMany
    {
        return $this->hasMany(Resena::class);
    }

/**
 * Calcula el saldo disponible del tutor:
 * Suma de monto_tutor de clases completadas y con pago liberado
 * menos los retiros ya solicitados (pendientes o pagados).
 */
public function saldoDisponible()
{
    $ganado = $this->clases()
        ->where('estado', 'completada')
        ->where('pago_liberado', 1)
        ->sum('monto_tutor');

    $retirado = $this->retiros()
        ->whereIn('estado', ['pendiente', 'pagado'])
        ->sum('monto');

    return round($ganado - $retirado, 2);
}

    public function disponibilidades(): HasMany
    {
        return $this->hasMany(Disponibilidad::class);
    }

    public function suscripciones(): HasMany
    {
        return $this->hasMany(Suscripcion::class);
    }

    public function suscripcionActiva(): HasOne
    {
        return $this->hasOne(Suscripcion::class)
        ->where('estado', 'activa')
        ->where('fecha_fin', '>=', now()->toDateString())
        ->latest();
}
}
