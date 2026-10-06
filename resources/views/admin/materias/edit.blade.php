@extends('layouts.app')

@section('title', 'Editar Materia')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass rounded-2xl p-6 text-white mb-6">
        <h1 class="text-3xl font-bold">✏️ Editar: {{ $materia->nombre }}</h1>
    </div>

    <div class="glass rounded-2xl p-6 text-white">
        <form method="POST" action="{{ route('admin.materias.update', $materia->id) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-2">Nombre *</label>
                <input type="text" name="nombre" value="{{ old('nombre', $materia->nombre) }}" required maxlength="100"
                       class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Descripción</label>
                <textarea name="descripcion" rows="2" maxlength="255"
                          class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">{{ old('descripcion', $materia->descripcion) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Icono actual</label>
                @if($materia->icono)
                    <img src="{{ Storage::url($materia->icono) }}" class="w-16 h-16 rounded-lg object-cover mb-2">
                @else
                    <p class="text-xs text-white/60 mb-2">Sin icono</p>
                @endif
                <input type="file" name="icono" accept="image/*"
                       class="w-full text-sm text-white/80 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-white/20 file:text-white hover:file:bg-white/30">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="activo" value="1" {{ $materia->activo ? 'checked' : '' }} class="rounded bg-white/20 border-white/40">
                <label class="text-sm">Activa</label>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-white text-brand-600 px-6 py-3 rounded-xl font-bold hover:bg-white/90 transition">
                    Guardar Cambios
                </button>
                <a href="{{ route('admin.materias.index') }}" class="bg-white/20 hover:bg-white/30 px-6 py-3 rounded-xl font-bold transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection