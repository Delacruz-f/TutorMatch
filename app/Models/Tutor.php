<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'materia_tutor', 'tutor_id', 'materia_id');
    }

    public function disponibilidades()
    {
        return $this->hasMany(Disponibilidad::class);
    }
}
