<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    public function estudiante()
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }

    public function pago()
    {
        return $this->hasOne(Pago::class);
    }

    public function resena()
    {
        return $this->hasOne(Resena::class);
    }
}
