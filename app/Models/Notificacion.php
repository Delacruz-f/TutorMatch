<?php

namespace App\Models;

use App\Events\NotificacionCreada;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    protected $fillable = [
        'user_id', 'tipo', 'titulo', 'mensaje', 'url', 'leida',
    ];

    protected $casts = [
        'leida' => 'boolean',
    ];

    /**
     * Tipos de notificación (se muestran como etiqueta en el historial).
     */
    public const TIPOS = [
        'clase' => 'Clases',
        'pago' => 'Pagos',
        'retiro' => 'Retiros',
        'suscripcion' => 'Suscripciones',
        'resena' => 'Reseñas',
        'sistema' => 'Sistema',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Normaliza la URL de destino: solo rutas relativas del propio sitio
     * (evita guardar/redirigir a URLs externas o con esquema).
     */
    public function setUrlAttribute($value): void
    {
        if (is_string($value) && preg_match('#^(https?:)?//#i', $value)) {
            $value = parse_url($value, PHP_URL_PATH) ?: null;
        }

        $this->attributes['url'] = $value ?: null;
    }

    public function scopeNoLeidas(Builder $query): Builder
    {
        return $query->where('leida', false);
    }

    public function scopeDeTipo(Builder $query, ?string $tipo): Builder
    {
        return $tipo ? $query->where('tipo', $tipo) : $query;
    }

    /**
     * Helper para crear notificación fácilmente desde cualquier controlador.
     */
    public static function enviar($userId, $titulo, $mensaje, $url = null, ?string $tipo = null)
    {
        // Si ese usuario ya tiene una notificación sin leer con el mismo
        // título y mensaje (ej. un cron ejecutado dos veces seguidas), no
        // se duplica.
        $existe = self::where('user_id', $userId)
            ->where('titulo', $titulo)
            ->where('mensaje', $mensaje)
            ->where('leida', false)
            ->exists();

        if ($existe) {
            return null;
        }

        $notificacion = self::create([
            'user_id' => $userId,
            'tipo' => $tipo ?? self::inferirTipo($titulo),
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'url' => $url,
        ]);

        if (! in_array(config('broadcasting.default'), ['log', 'null'], true)) {
            event(new NotificacionCreada($notificacion));
        }

        return $notificacion;
    }

    /**
     * Envía la misma notificación a varios usuarios.
     *
     * @param  iterable<int>  $userIds
     */
    public static function enviarA(iterable $userIds, $titulo, $mensaje, $url = null, ?string $tipo = null): void
    {
        foreach ($userIds as $userId) {
            self::enviar($userId, $titulo, $mensaje, $url, $tipo);
        }
    }

    /**
     * Envía a todos los usuarios con un rol (ej. 'administrador').
     */
    public static function enviarARol(string $rol, $titulo, $mensaje, $url = null, ?string $tipo = null): void
    {
        $ids = User::where('rol', $rol)->pluck('id');
        self::enviarA($ids, $titulo, $mensaje, $url, $tipo);
    }

    /**
     * Deduce el tipo a partir del título cuando no se especifica.
     */
    public static function inferirTipo(string $titulo): string
    {
        $t = mb_strtolower($titulo);

        return match (true) {
            str_contains($t, 'retiro') => 'retiro',
            str_contains($t, 'suscripci') || str_contains($t, 'plan') => 'suscripcion',
            str_contains($t, 'rese') => 'resena',
            str_contains($t, 'pago'), str_contains($t, 'pagad') => 'pago',
            str_contains($t, 'clase'), str_contains($t, 'sala') => 'clase',
            default => 'sistema',
        };
    }

    /**
     * URL segura de destino: solo rutas internas.
     */
    public function urlSegura(): ?string
    {
        $url = $this->url;

        if (! $url || preg_match('#^(https?:)?//#i', $url)) {
            return null;
        }

        return $url;
    }

    public function tipoLabel(): string
    {
        return self::TIPOS[$this->tipo] ?? 'Sistema';
    }
}
