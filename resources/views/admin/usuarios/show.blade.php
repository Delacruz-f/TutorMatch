@extends('layouts.app')

@section('title', 'Detalle de Usuario')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="glass rounded-2xl p-6 text-white">
        <div class="flex items-center gap-4">
            @if($usuario->foto)
                <img src="{{ Storage::url($usuario->foto) }}" class="w-20 h-20 rounded-full object-cover border-2 border-white/30">
            @else
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-brand-400 to-purple-500 flex items-center justify-center text-3xl font-bold">
                    {{ substr($usuario->nombre, 0, 1) }}
                </div>
            @endif
            <div class="flex-1">
                <h1 class="text-3xl font-bold">{{ $usuario->nombre }} {{ $usuario->apellido }}</h1>
                <p class="text-white/80">{{ $usuario->email }}</p>
                <div class="flex gap-2 mt-2">
                    <span class="bg-blue-400/30 px-3 py-1 rounded-full text-xs capitalize">{{ $usuario->rol }}</span>
                    <span class="{{ $usuario->estado === 'activo' ? 'bg-green-400/30' : 'bg-red-400/30' }} px-3 py-1 rounded-full text-xs">
                        {{ ucfirst($usuario->estado) }}
                    </span>
                </div>
            </div>
            <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-xl">
                ✏️ Editar
            </a>
        </div>
    </div>

    @if($usuario->tutor)
    <div class="glass rounded-2xl p-6 text-white">
        <h2 class="text-xl font-bold mb-4">📋 Información de Tutor</h2>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-white/60">Universidad</p>
                <p class="font-medium">{{ $usuario->tutor->universidad }}</p>
            </div>
            <div>
                <p class="text-white/60">Carrera</p>
                <p class="font-medium">{{ $usuario->tutor->carrera }}</p>
            </div>
            <div>
                <p class="text-white/60">Precio/hora</p>
                <p class="font-medium">S/ {{ $usuario->tutor->precio_hora }}</p>
            </div>
            <div>
                <p class="text-white/60">Aprobación</p>
                <p class="font-medium capitalize">{{ $usuario->tutor->aprobacion }}</p>
            </div>
            <div>
                <p class="text-white/60">Plan</p>
                <p class="font-medium capitalize">{{ $usuario->tutor->plan }}</p>
            </div>
            <div>
                <p class="text-white/60">Calificación</p>
                <p class="font-medium">⭐ {{ $usuario->tutor->calificacion_promedio }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="glass rounded-2xl p-6 text-white">
        <h2 class="text-xl font-bold mb-4">📊 Actividad</h2>
        <div class="grid grid-cols-3 gap-4 text-center">
            <div>
                <p class="text-3xl font-bold">{{ $usuario->notificaciones->count() }}</p>
                <p class="text-xs text-white/60">Notificaciones</p>
            </div>
            <div>
                <p class="text-3xl font-bold">{{ $usuario->created_at->diffForHumans() }}</p>
                <p class="text-xs text-white/60">Registro</p>
            </div>
            <div>
                <p class="text-3xl font-bold">{{ $usuario->email_verified_at ? '✅' : '❌' }}</p>
                <p class="text-xs text-white/60">Email verificado</p>
            </div>
        </div>
    </div>

    <a href="{{ route('admin.usuarios.index') }}" class="inline-block bg-white/20 hover:bg-white/30 px-6 py-3 rounded-xl text-white font-bold transition">
        ← Volver al listado
    </a>
</div>
@endsection