<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = [
        'clase_id', 'user_id', 'concepto', 'metodo', 'monto',
        'estado', 'referencia', 'comprobante_imagen', 'pagado_en',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'pagado_en' => 'datetime',
    ];

    public function clase()
    {
        return $this->belongsTo(Clase::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
