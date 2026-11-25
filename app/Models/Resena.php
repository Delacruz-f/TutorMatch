<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resena extends Model
{
    protected $table = 'resenas';

    protected $fillable = [
        'clase_id', 'estudiante_id', 'tutor_id',
        'calificacion', 'comentario', 'respuesta_tutor', 'estado',
    ];

    public function clase(): BelongsTo
    {
        return $this->belongsTo(Clase::class);
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Tutor::class);
    }
}
