<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retiro extends Model
{
    protected $table = 'retiros';

    protected $fillable = [
        'tutor_id', 'monto', 'metodo', 'numero_cuenta',
        'estado', 'procesado_en',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'procesado_en' => 'datetime',
    ];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
}
