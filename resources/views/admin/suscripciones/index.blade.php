@extends('layouts.app')

@section('title', 'Gestión de Suscripciones')

@section('content')
<div class="glass rounded-2xl p-6 text-white mb-6">
    <h1 class="text-3xl font-bold mb-1">⭐ Suscripciones</h1>
    <p class="text-white/80">Tutores con plan destacado o premium</p>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="glass rounded-2xl p-5 text-white">
        <p class="text-xs text-white/70 uppercase">Suscripciones Activas</p>
        <p class="text-3xl font-bold">{{ $activas }}</p>
    </div>
    <div class="glass rounded-2xl p-5 text-white">
        <p class="text-xs text-white/70 uppercase">Ingresos del Mes</p>
        <p class="text-3xl font-bold">S/ {{ number_format($ingresosMes, 2) }}</p>
    </div>
    <div class="glass rounded-2xl p-5 text-white">
        <p class="text-xs text-white/70 uppercase">Tutores Destacados</p>
        <p class="text-3xl font-bold">{{ $tutoresDestacados->count() }}</p>
    </div>
</div>

<!-- Tutores activos con plan -->
<div class="glass rounded-2xl p-6 text-white mb-6">
    <h2 class="text-xl font-bold mb-4">🏆 Tutores Destacados Activos</h2>
    @if($tutoresDestacados->isEmpty())
        <p class="text-white/60 text-sm">No hay tutores con plan activo actualmente.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($tutoresDestacados as $t)
                <div class="bg-white/10 rounded-xl p-4 border border-white/20">
                    <div class="flex items-center gap-3">
                        @if($t->user->foto)
                            <img src="{{ Storage::url($t->user->foto) }}" class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-400 to-purple-500 flex items-center justify-center font-bold">
                                {{ substr($t->user->nombre, 0, 1) }}
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="font-bold text-sm">{{ $t->user->nombre }} {{ $t->user->apellido }}</p>
                            <p class="text-xs text-white/70">
                                <span class="{{ $t->plan === 'premium' ? 'text-yellow-300' : 'text-blue-300' }}">
                                    {{ $t->plan === 'premium' ? '👑 Premium' : '⭐ Destacado' }}
                                </span>
                                · Vence {{ $t->plan_vence->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Filtros -->
<div class="glass rounded-2xl p-4 text-white mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <select name="estado" class="px-4 py-2 rounded-xl bg-white/15 border border-white/30 text-white">
            <option value="" class="text-gray-800">Todos los estados</option>
            <option value="activa" {{ request('estado') == 'activa' ? 'selected' : '' }} class="text-gray-800">Activa</option>
            <option value="vencida" {{ request('estado') == 'vencida' ? 'selected' : '' }} class="text-gray-800">Vencida</option>
            <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }} class="text-gray-800">Cancelada</option>
        </select>
        <select name="plan" class="px-4 py-2 rounded-xl bg-white/15 border border-white/30 text-white">
            <option value="" class="text-gray-800">Todos los planes</option>
            <option value="destacado" {{ request('plan') == 'destacado' ? 'selected' : '' }} class="text-gray-800">Destacado</option>
            <option value="premium" {{ request('plan') == 'premium' ? 'selected' : '' }} class="text-gray-800">Premium</option>
        </select>
        <div></div>
        <button type="submit" class="bg-white text-brand-600 py-2 rounded-xl font-bold hover:bg-white/90">Filtrar</button>
    </form>
</div>

<!-- Tabla -->
<div class="glass rounded-2xl p-6 text-white">
    @if($suscripciones->isEmpty())
        <p class="text-center py-8 text-white/70">No hay suscripciones registradas.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-white/20">
                        <th class="text-left p-3 font-semibold text-white/80">Tutor</th>
                        <th class="text-left p-3 font-semibold text-white/80">Plan</th>
                        <th class="text-left p-3 font-semibold text-white/80">Monto</th>
                        <th class="text-left p-3 font-semibold text-white/80">Inicio</th>
                        <th class="text-left p-3 font-semibold text-white/80">Fin</th>
                        <th class="text-left p-3 font-semibold text-white/80">Estado</th>
                        <th class="text-left p-3 font-semibold text-white/80">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suscripciones as $s)
                        <tr class="border-b border-white/10 hover:bg-white/5">
                            <td class="p-3">{{ $s->tutor->user->nombre }} {{ $s->tutor->user->apellido }}</td>
                            <td class="p-3 capitalize">{{ $s->plan }}</td>
                            <td class="p-3">S/ {{ $s->monto }}</td>
                            <td class="p-3 text-xs">{{ $s->fecha_inicio->format('d/m/Y') }}</td>
                            <td class="p-3 text-xs">{{ $s->fecha_fin->format('d/m/Y') }}</td>
                            <td class="p-3">
                                <span class="text-xs px-2 py-1 rounded
                                    {{ $s->estado === 'activa' ? 'bg-green-400/30' : '' }}
                                    {{ $s->estado === 'vencida' ? 'bg-yellow-400/30' : '' }}
                                    {{ $s->estado === 'cancelada' ? 'bg-red-400/30' : '' }}">
                                    {{ ucfirst($s->estado) }}
                                </span>
                            </td>
                            <td class="p-3">
                                <div class="flex gap-1">
                                    @if($s->estado === 'activa')
                                        <form method="POST" action="{{ route('admin.suscripciones.extender', $s->id) }}" class="inline flex gap-1">
                                            @csrf
                                            <input type="number" name="meses" value="1" min="1" max="12" class="w-14 px-2 py-1 rounded bg-white/15 border border-white/30 text-white text-xs">
                                            <button class="bg-blue-500/40 hover:bg-blue-500/60 px-2 py-1 rounded text-xs" title="Extender">➕</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.suscripciones.cancelar', $s->id) }}" onsubmit="return confirm('¿Cancelar esta suscripción?')">
                                            @csrf
                                            <button class="bg-red-500/40 hover:bg-red-500/60 px-2 py-1 rounded text-xs">❌</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $suscripciones->links() }}</div>
    @endif
</div>
@endsection