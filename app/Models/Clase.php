<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Clase extends Model
{
    protected $table = 'clases';

    protected $fillable = [
        'estudiante_id', 'tutor_id', 'materia_id',
        'fecha', 'hora_inicio', 'hora_fin', 'duracion_min',
        'precio_total', 'comision_porcentaje', 'comision_monto', 'monto_tutor',
        'estado', 'sala_jitsi', 'notas', 'pago_liberado', 'recordatorio_enviado',
    ];

    protected $casts = [
        'fecha' => 'date',
        'precio_total' => 'decimal:2',
        'comision_porcentaje' => 'decimal:2',
        'comision_monto' => 'decimal:2',
        'monto_tutor' => 'decimal:2',
        'pago_liberado' => 'boolean',
        'recordatorio_enviado' => 'boolean',
    ];

    // Relaciones
    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Tutor::class);
    }

    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class);
    }

    public function pago(): HasOne
    {
        return $this->hasOne(Pago::class);
    }

    public function resena(): HasOne
    {
        return $this->hasOne(Resena::class);
    }

    // Scopes

    /**
     * Clases en estado pendiente (solicitudes esperando respuesta del tutor).
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }
}
