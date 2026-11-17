<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tutor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TutorController extends Controller
{
    /**
     * GET /api/tutores
     *
     * Filtros opcionales: materia_id, precio_max, calificacion_min, dia_semana.
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'materia_id' => ['nullable', 'integer', 'exists:materias,id'],
            'precio_max' => ['nullable', 'numeric', 'min:0'],
            'calificacion_min' => ['nullable', 'numeric', 'between:0,5'],
            'dia_semana' => ['nullable', 'integer', 'between:1,7'],
        ]);

        $query = Tutor::with(['user:id,nombre,apellido,foto', 'materias:id,nombre,slug', 'disponibilidades'])
            ->where('aprobacion', 'aprobado')
            ->whereHas('user', function ($q) {
                $q->where('estado', 'activo');
            });

        if ($request->filled('materia_id')) {
            $query->whereHas('materias', function ($q) use ($request) {
                $q->where('materias.id', $request->materia_id);
            });
        }

        if ($request->filled('precio_max')) {
            $query->where('precio_hora', '<=', $request->precio_max);
        }

        if ($request->filled('calificacion_min')) {
            $query->where('calificacion_promedio', '>=', $request->calificacion_min);
        }

        if ($request->filled('dia_semana')) {
            $query->whereHas('disponibilidades', function ($q) use ($request) {
                $q->where('dia_semana', $request->dia_semana);
            });
        }

        $tutores = $query->orderBy('calificacion_promedio', 'desc')
            ->orderBy('id')
            ->paginate(15);

        return response()->json([
            'data' => $tutores->getCollection()
                ->map(fn (Tutor $tutor) => $this->publicData($tutor))
                ->values(),
            'meta' => [
                'current_page' => $tutores->currentPage(),
                'per_page' => $tutores->perPage(),
                'total' => $tutores->total(),
                'last_page' => $tutores->lastPage(),
            ],
        ]);
    }

    /**
     * GET /api/tutores/{id}
     */
    public function show(int $id): JsonResponse
    {
        $tutor = Tutor::with(['user:id,nombre,apellido,foto,biografia', 'materias:id,nombre,slug', 'disponibilidades'])
            ->where('aprobacion', 'aprobado')
            ->whereHas('user', function ($q) {
                $q->where('estado', 'activo');
            })
            ->findOrFail($id);

        return response()->json([
            'data' => $this->publicData($tutor),
        ]);
    }

    /**
     * Datos públicos de un tutor: sin email, teléfono, DNI, CV ni datos de pagos.
     *
     * @return array<string, mixed>
     */
    private function publicData(Tutor $tutor): array
    {
        return [
            'id' => $tutor->id,
            'nombre' => $tutor->user?->nombre,
            'apellido' => $tutor->user?->apellido,
            'foto' => $tutor->user?->foto,
            'biografia' => $tutor->user?->biografia,
            'universidad' => $tutor->universidad,
            'carrera' => $tutor->carrera,
            'precio_hora' => $tutor->precio_hora,
            'calificacion_promedio' => $tutor->calificacion_promedio,
            'total_resenas' => $tutor->total_resenas,
            'plan' => $tutor->plan,
            'materias' => $tutor->materias->map(fn ($m) => [
                'id' => $m->id,
                'nombre' => $m->nombre,
                'slug' => $m->slug,
            ])->values(),
            'disponibilidades' => $tutor->disponibilidades->map(fn ($d) => [
                'dia_semana' => $d->dia_semana,
                'hora_inicio' => $d->hora_inicio,
                'hora_fin' => $d->hora_fin,
            ])->values(),
        ];
    }
}
