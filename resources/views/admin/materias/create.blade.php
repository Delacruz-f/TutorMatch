@extends('layouts.app')

@section('title', 'Nueva Materia')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass rounded-2xl p-6 text-white mb-6">
        <h1 class="text-3xl font-bold">➕ Nueva Materia</h1>
    </div>

    <div class="glass rounded-2xl p-6 text-white">
        <form method="POST" action="{{ route('admin.materias.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-2">Nombre *</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required maxlength="100"
                       class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
                <p class="text-xs text-white/60 mt-1">El slug se genera automáticamente.</p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Descripción</label>
                <textarea name="descripcion" rows="2" maxlength="255"
                          class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">{{ old('descripcion') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Icono (JPG, PNG, SVG, máx 1MB)</label>
                <input type="file" name="icono" accept="image/*"
                       class="w-full text-sm text-white/80 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-white/20 file:text-white hover:file:bg-white/30">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="activo" value="1" checked class="rounded bg-white/20 border-white/40">
                <label class="text-sm">Activa (visible en búsquedas y registro de tutores)</label>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-white text-brand-600 px-6 py-3 rounded-xl font-bold hover:bg-white/90 transition">
                    Crear Materia
                </button>
                <a href="{{ route('admin.materias.index') }}" class="bg-white/20 hover:bg-white/30 px-6 py-3 rounded-xl font-bold transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection