<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    protected $table = 'materias';

    protected $fillable = [
        'nombre', 'slug', 'descripcion', 'icono', 'activo'
    ];

    public function tutores()
    {
        return $this->belongsToMany(Tutor::class, 'materia_tutor', 'materia_id', 'tutor_id');
    }
}
