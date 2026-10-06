@extends('layouts.app')

@section('title', $tutor->user->nombre . ' ' . $tutor->user->apellido)

@section('content')

{{-- Botón volver --}}
<a href="{{ route('busqueda.index') }}" class="inline-flex items-center gap-2 text-white/80 hover:text-white mb-4 transition">
    ← Volver a búsqueda
</a>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- COLUMNA IZQUIERDA -->
    <div class="lg:col-span-2 space-y-6">

        {{-- Card principal del tutor --}}
        <div class="glass rounded-2xl p-6 text-white">
            <div class="flex flex-col sm:flex-row items-start gap-6">
                @if($tutor->user->foto)
                    <img src="{{ Storage::url($tutor->user->foto) }}" class="w-28 h-28 rounded-2xl object-cover border-2 border-white/40 shadow-lg">
                @else
                    <div class="w-28 h-28 rounded-2xl bg-gradient-to-br from-brand-400 to-purple-500 flex items-center justify-center text-4xl font-bold border-2 border-white/40 shadow-lg">
                        {{ substr($tutor->user->nombre, 0, 1) }}
                    </div>
                @endif

                <div class="flex-1">
                    <div class="flex flex-wrap items-start gap-3 mb-2">
                        <h1 class="text-3xl font-bold">
                            {{ $tutor->user->nombre }} {{ $tutor->user->apellido }}
                        </h1>
                        @if($tutor->plan !== 'basico')
                            <span class="{{ $tutor->plan === 'premium' ? 'bg-purple-500/40 border-purple-300/50' : 'bg-yellow-500/40 border-yellow-300/50' }} text-xs font-bold px-3 py-1 rounded-full border">
                                {{ $tutor->plan === 'premium' ? '👑 PREMIUM' : '⭐ DESTACADO' }}
                            </span>
                        @endif
                    </div>

                    <p class="text-white/80 mb-3">
                        🎓 {{ $tutor->carrera }} · {{ $tutor->universidad }}
                    </p>

                    <div class="flex items-center gap-2 bg-white/15 rounded-xl px-3 py-2 inline-flex border border-white/20">
                        <span class="text-yellow-300 text-lg">⭐</span>
                        <span class="font-bold text-lg">{{ number_format($tutor->calificacion_promedio, 1) }}</span>
                        <span class="text-white/70 text-sm">({{ $tutor->total_resenas }} reseñas)</span>
                    </div>
                </div>
            </div>

            {{-- Biografía --}}
            @if($tutor->user->biografia)
                <div class="mt-6 pt-6 border-t border-white/20">
                    <h2 class="font-bold text-white/90 mb-2 flex items-center gap-2">👤 Sobre mí</h2>
                    <p class="text-white/80 leading-relaxed">{{ $tutor->user->biografia }}</p>
                </div>
            @endif

            {{-- Materias --}}
            <div class="mt-6 pt-6 border-t border-white/20">
                <h2 class="font-bold text-white/90 mb-3 flex items-center gap-2">📚 Materias que enseño</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($tutor->materias as $m)
                        <span class="bg-white/20 text-white px-3 py-1.5 rounded-full text-sm border border-white/20">
                            {{ $m->nombre }}
                        </span>
                    @endforeach
                </div>
            </div>

            {{-- Disponibilidad --}}
            <div class="mt-6 pt-6 border-t border-white/20">
                <h2 class="font-bold text-white/90 mb-3 flex items-center gap-2">🕐 Disponibilidad</h2>
                @if($tutor->disponibilidades->isEmpty())
                    <p class="text-white/60 text-sm">Sin disponibilidad configurada.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                        @foreach($tutor->disponibilidades as $d)
                            <div class="bg-white/10 p-3 rounded-xl border border-white/20 flex items-center gap-2">
                                <span class="text-white/60">📅</span>
                                <span class="font-medium">
                                    @php
                                        $dias = ['', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                                        echo $dias[$d->dia_semana];
                                    @endphp
                                </span>
                                <span class="text-white/70 ml-auto text-xs">
                                    {{ substr($d->hora_inicio, 0, 5) }} - {{ substr($d->hora_fin, 0, 5) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Reseñas --}}
        <div class="glass rounded-2xl p-6 text-white">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">⭐ Reseñas</h2>
                <span class="bg-white/15 px-3 py-1 rounded-full text-sm">
                    {{ $resenas->count() }} total
                </span>
            </div>

            @if($resenas->isEmpty())
                <div class="text-center py-8">
                    <div class="text-4xl mb-2">💬</div>
                    <p class="text-white/60">Aún no hay reseñas para este tutor.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($resenas as $r)
                        <div class="bg-white/10 rounded-xl p-4 border border-white/15">
                            <div class="flex items-center gap-3 mb-2">
                                @if($r->estudiante->foto)
                                    <img src="{{ Storage::url($r->estudiante->foto) }}" class="w-9 h-9 rounded-full object-cover">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-400 to-purple-500 flex items-center justify-center text-sm font-bold">
                                        {{ substr($r->estudiante->nombre, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <span class="font-bold">{{ $r->estudiante->nombre }}</span>
                                    <p class="text-yellow-300 text-sm">{{ str_repeat('⭐', $r->calificacion) }}</p>
                                </div>
                            </div>

                            @if($r->comentario)
                                <p class="text-white/90 mt-2">{{ $r->comentario }}</p>
                            @endif

                            @if($r->respuesta_tutor)
                                <div class="mt-3 ml-4 p-3 bg-blue-400/20 rounded-xl border-l-4 border-blue-400">
                                    <p class="text-xs font-bold text-blue-200">Respuesta del tutor:</p>
                                    <p class="text-sm mt-1">{{ $r->respuesta_tutor }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- COLUMNA DERECHA: RESERVAR -->
    <div class="lg:col-span-1">
        <div class="glass rounded-2xl p-6 text-white sticky top-20">

            <div class="text-center mb-6 pb-6 border-b border-white/20">
                <p class="text-sm text-white/70 mb-1">Precio por hora</p>
                <span class="text-4xl font-bold text-green-300">S/ {{ $tutor->precio_hora }}</span>
            </div>

            @auth
                @if(Auth::user()->rol === 'estudiante')
                    <form action="{{ route('reservas.create', $tutor->id) }}" method="GET" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Elige una materia:</label>
                            <select name="materia_id" required
                                    class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
                                @foreach($tutor->materias as $m)
                                    <option value="{{ $m->id }}" class="text-gray-800">{{ $m->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="w-full bg-white text-brand-600 py-3 rounded-xl font-bold hover:bg-white/90 transition shadow-lg">
                            📅 Reservar Clase
                        </button>
                    </form>

                    <div class="mt-4 p-3 bg-white/10 rounded-xl border border-white/15">
                        <p class="text-xs text-white/70 leading-relaxed">
                            💡 <strong>Tip:</strong> Revisa la disponibilidad del tutor antes de reservar.
                        </p>
                    </div>
                @else
                    <div class="bg-white/10 rounded-xl p-4 border border-white/15 text-center">
                        <p class="text-white/70 text-sm">
                            Solo los estudiantes pueden reservar clases.
                        </p>
                    </div>
                @endif
            @else
                <a href="{{ route('login') }}"
                   class="block w-full bg-white text-brand-600 py-3 rounded-xl font-bold text-center hover:bg-white/90 transition shadow-lg">
                    🔐 Inicia sesión para reservar
                </a>
                <p class="text-xs text-white/60 text-center mt-3">
                    ¿No tienes cuenta?
                    <a href="{{ route('register') }}" class="text-white underline">Regístrate gratis</a>
                </p>
            @endauth

            {{-- Garantías --}}
            <div class="mt-6 pt-6 border-t border-white/20 space-y-3 text-xs text-white/70">
                <div class="flex items-center gap-2">
                    <span>✅</span>
                    <span>Tutor verificado por el admin</span>
                </div>
                <div class="flex items-center gap-2">
                    <span>🎥</span>
                    <span>Clases en vivo con Jitsi</span>
                </div>
                <div class="flex items-center gap-2">
                    <span>💳</span>
                    <span>Pago seguro con Yape/Plin/Tarjeta</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection