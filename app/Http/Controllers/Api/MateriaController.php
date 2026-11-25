<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Materia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    /**
     * GET /api/materias
     *
     * Solo materias activas. Parámetro opcional: ?q=texto
     */
    public function index(Request $request): JsonResponse
    {
        $query = Materia::query()
            ->where('activo', 1)
            ->select(['id', 'nombre', 'slug', 'descripcion', 'icono']);

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('nombre', 'like', "%{$q}%")
                    ->orWhere('descripcion', 'like', "%{$q}%");
            });
        }

        $materias = $query->orderBy('nombre')->get();

        return response()->json([
            'data' => $materias,
        ]);
    }

    /**
     * GET /api/materias/{id}
     */
    public function show(int $id): JsonResponse
    {
        $materia = Materia::where('activo', 1)
            ->select(['id', 'nombre', 'slug', 'descripcion', 'icono'])
            ->findOrFail($id);

        return response()->json([
            'data' => $materia,
        ]);
    }
}
