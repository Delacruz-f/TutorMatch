<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suscripcion extends Model
{
    protected $table = 'suscripciones';

    protected $fillable = [
        'tutor_id', 'plan', 'monto', 'fecha_inicio', 'fecha_fin', 'estado',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
}
