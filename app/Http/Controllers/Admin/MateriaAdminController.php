<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
class MateriaAdminController extends Controller
{
    public function index()
    {
        $materias = Materia::withCount('tutores')
            ->orderBy('activo', 'desc')
            ->orderBy('nombre')
            ->paginate(20);

        return view('admin.materias.index', compact('materias'));
    }

    public function create()
    {
        return view('admin.materias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'icono' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:1024',
            'activo' => 'nullable|boolean',
        ]);

        $slug = Str::slug($request->nombre);

        // Si el slug ya existe, agregar sufijo numérico
        $slugBase = $slug;
        $i = 1;
        while (Materia::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $i++;
        }

        $iconoPath = null;
        if ($request->hasFile('icono')) {
            $iconoPath = $request->file('icono')->store('materias', 'public');
        }

        Materia::create([
            'nombre' => $request->nombre,
            'slug' => $slug,
            'descripcion' => $request->descripcion,
            'icono' => $iconoPath,
            'activo' => $request->boolean('activo', true),
        ]);

        return redirect()->route('admin.materias.index')
            ->with('success', 'Materia creada correctamente.');
    }

    public function edit($id)
    {
        $materia = Materia::findOrFail($id);
        return view('admin.materias.edit', compact('materia'));
    }

    public function update(Request $request, $id)
    {
        $materia = Materia::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'icono' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:1024',
            'activo' => 'nullable|boolean',
        ]);

        $data = [
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'activo' => $request->boolean('activo'),
        ];

        if ($request->hasFile('icono')) {
            // Eliminar icono anterior
           if ($materia->icono && Storage::disk('public')->exists($materia->icono)) {
    Storage::disk('public')->delete($materia->icono);
}
            $data['icono'] = $request->file('icono')->store('materias', 'public');
        }

        $materia->update($data);

        return redirect()->route('admin.materias.index')
            ->with('success', 'Materia actualizada correctamente.');
    }

    public function toggleActivo($id)
    {
        $materia = Materia::findOrFail($id);
        $materia->update(['activo' => !$materia->activo]);

        return back()->with('success', 'Estado actualizado.');
    }

    public function destroy($id)
    {
        $materia = Materia::findOrFail($id);

        // Verificar que no tenga tutores asignados
        if ($materia->tutores()->count() > 0) {
            return back()->with('error', 'No puedes eliminar una materia con tutores asignados. Desactívala en su lugar.');
        }

        if ($materia->icono && Storage::disk('public')->exists($materia->icono)) {
    Storage::disk('public')->delete($materia->icono);
}

        $materia->delete();

        return back()->with('success', 'Materia eliminada.');
    }
}
