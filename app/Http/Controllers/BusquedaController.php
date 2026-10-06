<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use App\Models\Materia;
use Illuminate\Http\Request;

class BusquedaController extends Controller
{
    /**
     * Listado de tutores con filtros
     */
    public function index(Request $request)
{
    $materias = Materia::where('activo', 1)->orderBy('nombre')->get();

    $query = Tutor::with(['user', 'materias', 'disponibilidades'])
        ->where('aprobacion', 'aprobado')
        ->whereHas('user', function ($q) {
            $q->where('estado', 'activo');
        });

    // Filtro por materia
    if ($request->filled('materia_id')) {
        $query->whereHas('materias', function ($q) use ($request) {
            $q->where('materias.id', $request->materia_id);
        });
    }

    // Filtro por precio máximo
    if ($request->filled('precio_max')) {
        $query->where('precio_hora', '<=', $request->precio_max);
    }

    // Filtro por calificación mínima
    if ($request->filled('calificacion_min')) {
        $query->where('calificacion_promedio', '>=', $request->calificacion_min);
    }

    // NUEVO: Filtro por día de la semana
    if ($request->filled('dia_semana')) {
        $query->whereHas('disponibilidades', function ($q) use ($request) {
            $q->where('dia_semana', $request->dia_semana);
        });
    }

    // NUEVO: Filtro por rango horario (ej: clases entre 16:00 y 20:00)
    if ($request->filled('hora_desde') && $request->filled('hora_hasta')) {
        $query->whereHas('disponibilidades', function ($q) use ($request) {
            $q->where('hora_inicio', '<=', $request->hora_desde)
              ->where('hora_fin', '>=', $request->hora_hasta);
        });
    }

    // Ordenamiento: primero los destacados/premium, luego por calificación
    $query->orderByRaw("FIELD(plan, 'premium', 'destacado', 'basico')")
          ->orderBy('calificacion_promedio', 'desc')
          ->orderBy('created_at', 'desc');

    $tutores = $query->paginate(9)->withQueryString();

    return view('busqueda.index', compact('tutores', 'materias'));
}

    /**
     * Perfil público del tutor
     */
    public function verTutor($id)
    {
        $tutor = Tutor::with([
            'user',
            'materias',
            'disponibilidades',
        ])
        ->where('aprobacion', 'aprobado')
        ->findOrFail($id);

        // Obtener reseñas visibles del tutor
        $resenas = \App\Models\Resena::with('estudiante')
            ->where('tutor_id', $tutor->id)
            ->where('estado', 'visible')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('busqueda.tutor', compact('tutor', 'resenas'));
    }
}
