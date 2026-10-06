@extends('layouts.app')

@section('title', 'Panel del Tutor')

@section('content')

<div class="glass rounded-2xl p-6 text-white mb-6"> <h1 class="text-3xl font-bold mb-1">Panel del Tutor</h1> <p class="text-white/80">Bienvenido, {{ Auth::user()->nombre }} 👨‍🏫</p> </div>

@if(!Auth::user()->tutor)

<div class="glass rounded-2xl p-6 text-white border-l-4 border-blue-400">
    <h2 class="text-xl font-bold mb-2">📋 Completa tu perfil</h2>

    <p class="text-white/80 mb-4">
        Para empezar a recibir solicitudes, completa tu perfil y sube tus documentos.
    </p>

    <a href="{{ route('tutor.register.form') }}"
       class="inline-block bg-white text-brand-600 px-6 py-3 rounded-xl font-bold hover:bg-white/90 transition">
        Completar Perfil →
    </a>
</div>


@elseif(Auth::user()->tutor->aprobacion === 'pendiente')

<div class="glass rounded-2xl p-6 text-white border-l-4 border-yellow-400">
    <p class="text-lg">
        <strong>⏳ Perfil en revisión</strong>
    </p>

    <p class="text-white/80 mt-1">
        Tu solicitud está siendo evaluada por el administrador.
    </p>
</div>


@elseif(Auth::user()->tutor->aprobacion === 'rechazado')

<div class="glass rounded-2xl p-6 text-white border-l-4 border-red-400">
    <p class="text-lg">
        <strong>❌ Perfil rechazado</strong>
    </p>

    <p class="text-white/80 mt-1">
        Motivo: {{ Auth::user()->tutor->motivo_rechazo }}
    </p>

    <a href="{{ route('tutor.register.form') }}"
       class="inline-block mt-3 underline text-white">
        Volver a intentar
    </a>
</div>


@else

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">

    {{-- Mis Clases --}}
    <a href="{{ route('tutor.dashboard') }}"
       class="glass rounded-2xl p-6 text-white hover:bg-white/25 transition group">

        <div class="text-4xl mb-3 group-hover:scale-110 transition">
            📊
        </div>

        <h3 class="font-bold">
            Mis Clases
        </h3>

        <p class="text-sm text-white/70">
            Acepta o completa clases
        </p>
    </a>

    {{-- Retiros --}}
    <a href="{{ route('tutor.retiros.index') }}"
       class="glass rounded-2xl p-6 text-white hover:bg-white/25 transition group">

        <div class="text-4xl mb-3 group-hover:scale-110 transition">
            💰
        </div>

        <h3 class="font-bold">
            Retiros
        </h3>

        <p class="text-sm text-white/70">
            Solicita tu dinero
        </p>
    </a>

    {{-- Suscripción --}}
    <a href="{{ route('tutor.suscripciones.index') }}"
       class="glass rounded-2xl p-6 text-white hover:bg-white/25 transition group">

        <div class="text-4xl mb-3 group-hover:scale-110 transition">
            ⭐
        </div>

        <h3 class="font-bold">
            Suscripción
        </h3>

        <p class="text-sm text-white/70">
            Aparece destacado
        </p>
    </a>

    {{-- Disponibilidad --}}
    <a href="{{ route('tutor.disponibilidad.index') }}"
       class="glass rounded-2xl p-6 text-white hover:bg-white/25 transition group">

        <div class="text-4xl mb-3 group-hover:scale-110 transition">
            🕐
        </div>

        <h3 class="font-bold">
            Disponibilidad
        </h3>

        <p class="text-sm text-white/70">
            Configura tus horarios
        </p>
    </a>

    {{-- Mis Reseñas --}}
    <a href="{{ route('tutor.resenas.index') }}"
       class="glass rounded-2xl p-6 text-white hover:bg-white/25 transition group">

        <div class="text-4xl mb-3 group-hover:scale-110 transition">
            ⭐
        </div>

        <h3 class="font-bold">
            Mis Reseñas
        </h3>

        <p class="text-sm text-white/70">
            Responde a tus estudiantes
        </p>
    </a>

</div>


@endif
@endsection