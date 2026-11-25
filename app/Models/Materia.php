<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Materia extends Model
{
    protected $table = 'materias';

    protected $fillable = [
        'nombre', 'slug', 'descripcion', 'icono', 'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function tutores(): BelongsToMany
    {
        return $this->belongsToMany(Tutor::class, 'materia_tutor', 'materia_id', 'tutor_id');
    }

    public function clases(): HasMany
    {
        return $this->hasMany(Clase::class);
    }
}
