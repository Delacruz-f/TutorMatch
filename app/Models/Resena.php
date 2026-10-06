<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resena extends Model
{
    protected $table = 'resenas';

    protected $fillable = [
        'clase_id', 'estudiante_id', 'tutor_id',
        'calificacion', 'comentario', 'respuesta_tutor', 'estado',
    ];

    public function clase()
    {
        return $this->belongsTo(Clase::class);
    }

    public function estudiante()
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
}
