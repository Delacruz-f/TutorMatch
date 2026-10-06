@extends('layouts.app')

@section('title', 'Buscar Tutores')

@section('content')

<!-- Header -->
<div class="glass rounded-2xl p-6 text-white mb-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold mb-1">🔍 Buscar Tutores</h1>
            <p class="text-white/80">Encuentra al tutor perfecto para ti</p>
        </div>
        <div class="bg-white/15 rounded-xl px-4 py-2 border border-white/20">
            <p class="text-xs text-white/70">Resultados</p>
            <p class="text-2xl font-bold">{{ $tutores->total() }}</p>
        </div>
    </div>
</div>

<!-- Formulario de filtros -->
<div class="glass rounded-2xl p-6 text-white mb-6">
    <form method="GET" action="{{ route('busqueda.index') }}" class="space-y-4">
        <!-- Fila 1 -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium mb-2">Materia</label>
                <select name="materia_id" class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
                    <option value="" class="text-gray-800">Todas</option>
                    @foreach($materias as $m)
                        <option value="{{ $m->id }}" {{ request('materia_id') == $m->id ? 'selected' : '' }} class="text-gray-800">
                            {{ $m->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Precio máx (S/)</label>
                <input type="number" name="precio_max" value="{{ request('precio_max') }}" min="5" step="5"
                       placeholder="Ej: 50"
                       class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white placeholder-white/50 focus:bg-white/25 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Calificación mín</label>
                <select name="calificacion_min" class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
                    <option value="" class="text-gray-800">Cualquiera</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ request('calificacion_min') == $i ? 'selected' : '' }} class="text-gray-800">
                            {{ $i }}+ ⭐
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Día disponible</label>
                <select name="dia_semana" class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
                    <option value="" class="text-gray-800">Cualquier día</option>
                    <option value="1" {{ request('dia_semana') == '1' ? 'selected' : '' }} class="text-gray-800">Lunes</option>
                    <option value="2" {{ request('dia_semana') == '2' ? 'selected' : '' }} class="text-gray-800">Martes</option>
                    <option value="3" {{ request('dia_semana') == '3' ? 'selected' : '' }} class="text-gray-800">Miércoles</option>
                    <option value="4" {{ request('dia_semana') == '4' ? 'selected' : '' }} class="text-gray-800">Jueves</option>
                    <option value="5" {{ request('dia_semana') == '5' ? 'selected' : '' }} class="text-gray-800">Viernes</option>
                    <option value="6" {{ request('dia_semana') == '6' ? 'selected' : '' }} class="text-gray-800">Sábado</option>
                    <option value="7" {{ request('dia_semana') == '7' ? 'selected' : '' }} class="text-gray-800">Domingo</option>
                </select>
            </div>
        </div>

        <!-- Fila 2 -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium mb-2">Desde hora</label>
                <input type="time" name="hora_desde" value="{{ request('hora_desde') }}"
                       class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Hasta hora</label>
                <input type="time" name="hora_hasta" value="{{ request('hora_hasta') }}"
                       class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-white text-brand-600 py-3 rounded-xl font-bold hover:bg-white/90 transition">
                    🔍 Buscar
                </button>
            </div>
            <div class="flex items-end">
                <a href="{{ route('busqueda.index') }}" class="w-full bg-white/20 hover:bg-white/30 text-white text-center py-3 rounded-xl font-bold transition">
                    Limpiar filtros
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Resultados -->
@if($tutores->isEmpty())
    <div class="glass rounded-2xl p-12 text-white text-center">
        <div class="text-6xl mb-4">🔍</div>
        <p class="text-xl font-bold mb-2">No se encontraron tutores</p>
        <p class="text-white/70 mb-4">Prueba con otros filtros o amplía tu búsqueda.</p>
        <a href="{{ route('busqueda.index') }}" class="inline-block bg-white text-brand-600 px-6 py-3 rounded-xl font-bold hover:bg-white/90 transition">
            Ver todos los tutores
        </a>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($tutores as $tutor)
            <div class="glass rounded-2xl overflow-hidden hover:bg-white/25 transition group">

                {{-- Badge plan destacado/premium --}}
                @if($tutor->plan !== 'basico')
                    <div class="{{ $tutor->plan === 'premium' ? 'bg-gradient-to-r from-purple-500 to-pink-500' : 'bg-gradient-to-r from-yellow-400 to-amber-500' }} text-white text-xs font-bold px-3 py-1.5 text-center">
                        {{ $tutor->plan === 'premium' ? '👑 PREMIUM' : '⭐ DESTACADO' }}
                    </div>
                @endif

                <div class="p-6 text-white">
                    {{-- Foto y datos --}}
                    <div class="flex items-center mb-4">
                        @if($tutor->user->foto)
                            <img src="{{ Storage::url($tutor->user->foto) }}" alt="Foto"
                                 class="w-16 h-16 rounded-full object-cover border-2 border-white/40">
                        @else
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-brand-400 to-purple-500 flex items-center justify-center text-2xl font-bold border-2 border-white/40">
                                {{ substr($tutor->user->nombre, 0, 1) }}
                            </div>
                        @endif
                        <div class="ml-4 flex-1 min-w-0">
                            <h3 class="text-lg font-bold truncate">
                                {{ $tutor->user->nombre }} {{ $tutor->user->apellido }}
                            </h3>
                            <p class="text-sm text-white/70 truncate">{{ $tutor->carrera }}</p>
                            <div class="flex items-center mt-1">
                                <span class="text-yellow-300">⭐</span>
                                <span class="text-sm ml-1">
                                    <strong>{{ number_format($tutor->calificacion_promedio, 1) }}</strong>
                                    <span class="text-white/60">({{ $tutor->total_resenas }})</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Materias --}}
                    <div class="mb-4">
                        <p class="text-xs text-white/60 uppercase font-bold mb-2">Materias</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach($tutor->materias->take(3) as $m)
                                <span class="bg-white/20 text-white text-xs px-2.5 py-1 rounded-full border border-white/20">
                                    {{ $m->nombre }}
                                </span>
                            @endforeach
                            @if($tutor->materias->count() > 3)
                                <span class="bg-white/10 text-white/70 text-xs px-2.5 py-1 rounded-full">
                                    +{{ $tutor->materias->count() - 3 }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Precio y CTA --}}
                    <div class="flex justify-between items-center pt-4 border-t border-white/20">
                        <div>
                            <span class="text-2xl font-bold text-green-300">S/ {{ $tutor->precio_hora }}</span>
                            <span class="text-xs text-white/60">/hora</span>
                        </div>
                        <a href="{{ route('busqueda.tutor', $tutor->id) }}"
                           class="bg-white text-brand-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-white/90 transition">
                            Ver Perfil →
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Paginación --}}
    <div class="mt-8">
        {{ $tutores->links() }}
    </div>
@endif
@endsection