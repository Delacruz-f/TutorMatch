@extends('layouts.app')

@section('title', 'Sala de Clase')

@section('content')
<div class="text-center py-12">
    <div class="text-6xl mb-4">🎥</div>
    <h2 class="text-xl font-bold mb-4">Tu sala está lista</h2>
    <p class="text-white/70 mb-6">Se abrirá en una nueva pestaña para mejor experiencia en tu dispositivo.</p>
    <a href="https://meet.jit.si/{{ $clase->sala_jitsi }}" target="_blank"
       class="inline-block bg-white text-brand-600 px-8 py-4 rounded-xl font-bold hover:bg-white/90">
        🎥 Abrir Videollamada
    </a>
</div>
<div class="bg-white rounded-lg shadow-md overflow-hidden" style="height: 85vh;">
    <div class="bg-blue-600 text-white p-3 flex justify-between items-center">
        <div>
            <h1 class="font-bold">{{ $clase->materia->nombre }} - {{ $clase->fecha->format('d/m/Y') }} {{ substr($clase->hora_inicio, 0, 5) }}</h1>
            <p class="text-sm">Sala: {{ $clase->sala_jitsi }}</p>
        </div>
        <a href="{{ Auth::user()->rol === 'tutor' ? route('tutor.dashboard') : route('estudiante.dashboard') }}"
           class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">
            Salir
        </a>
    </div>
    
    <iframe 
    src="https://meet.jit.si/{{ $clase->sala_jitsi }}"
    allow="camera; microphone; fullscreen; display-capture; autoplay"
    style="width: 100%; height: calc(100% - 70px); border: 0;">
</iframe>
</div>
@endsection
