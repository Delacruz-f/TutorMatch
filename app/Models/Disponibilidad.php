<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disponibilidad extends Model
{
    protected $table = 'disponibilidades';

    protected $fillable = [
        'tutor_id', 'dia_semana', 'hora_inicio', 'hora_fin'
    ];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
}
