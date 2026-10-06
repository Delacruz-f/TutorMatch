@extends('layouts.app')

@section('title', 'Gestión de Materias')

@section('content')
<div class="glass rounded-2xl p-6 text-white mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">Gestión de Materias</h1>
            <p class="text-white/80">Categorías disponibles en la plataforma</p>
        </div>
        <a href="{{ route('admin.materias.create') }}" class="bg-white text-brand-600 px-5 py-2.5 rounded-xl font-bold hover:bg-white/90 transition">
            ➕ Nueva Materia
        </a>
    </div>
</div>

<div class="glass rounded-2xl p-6 text-white">
    @if($materias->isEmpty())
        <p class="text-center py-8 text-white/70">No hay materias registradas.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-white/20">
                        <th class="text-left p-3 font-semibold text-white/80">Icono</th>
                        <th class="text-left p-3 font-semibold text-white/80">Materia</th>
                        <th class="text-left p-3 font-semibold text-white/80">Slug</th>
                        <th class="text-left p-3 font-semibold text-white/80">Tutores</th>
                        <th class="text-left p-3 font-semibold text-white/80">Estado</th>
                        <th class="text-left p-3 font-semibold text-white/80">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materias as $m)
                        <tr class="border-b border-white/10 hover:bg-white/5 transition">
                            <td class="p-3">
                                @if($m->icono)
                                    <img src="{{ Storage::url($m->icono) }}" class="w-10 h-10 rounded-lg object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">📚</div>
                                @endif
                            </td>
                            <td class="p-3">
                                <p class="font-medium">{{ $m->nombre }}</p>
                                <p class="text-xs text-white/60">{{ Str::limit($m->descripcion, 60) }}</p>
                            </td>
                            <td class="p-3 text-white/60 text-xs font-mono">{{ $m->slug }}</td>
                            <td class="p-3"><span class="bg-white/20 px-2 py-1 rounded-full text-xs">{{ $m->tutores_count }}</span></td>
                            <td class="p-3">
                                @if($m->activo)
                                    <span class="bg-green-400/30 text-green-100 border border-green-300/40 text-xs px-3 py-1 rounded-full">✅ Activa</span>
                                @else
                                    <span class="bg-gray-400/30 text-gray-100 border border-gray-300/40 text-xs px-3 py-1 rounded-full">⏸️ Inactiva</span>
                                @endif
                            </td>
                            <td class="p-3">
                                <div class="flex gap-1 flex-wrap">
                                    <form method="POST" action="{{ route('admin.materias.toggle', $m->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-yellow-500/40 hover:bg-yellow-500/60 px-2 py-1 rounded text-xs" title="Cambiar estado">
                                            {{ $m->activo ? '⏸️' : '▶️' }}
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.materias.edit', $m->id) }}" class="bg-blue-500/40 hover:bg-blue-500/60 px-2 py-1 rounded text-xs" title="Editar">✏️</a>
                                    <form method="POST" action="{{ route('admin.materias.destroy', $m->id) }}" class="inline" onsubmit="return confirm('¿Eliminar esta materia?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500/40 hover:bg-red-500/60 px-2 py-1 rounded text-xs" title="Eliminar">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $materias->links() }}</div>
    @endif
</div>
@endsection