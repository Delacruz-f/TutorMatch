<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    protected $fillable = [
        'user_id', 'titulo', 'mensaje', 'url', 'leida',
    ];

    protected $casts = [
        'leida' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper para crear notificación fácilmente desde cualquier controlador.
     */
    public static function enviar($userId, $titulo, $mensaje, $url = null)
    {
        return self::create([
            'user_id' => $userId,
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'url' => $url,
        ]);
    }
}
