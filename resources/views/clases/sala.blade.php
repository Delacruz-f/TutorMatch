@extends('layouts.app')

@section('title', __('ui.card_room'))

@section('content')
<div class="text-center py-12">
    <div class="icon-chip mx-auto mb-4 !w-16 !h-16 rounded-2xl"><x-icon name="video" class="!w-7 !h-7" /></div>
    <h2 class="text-xl font-bold mb-4">{{ __('ui.room_ready') }}</h2>
    <p class="text-white/70 mb-6">{{ __('ui.open_new_tab') }}</p>
    <a href="https://meet.jit.si/{{ $clase->sala_jitsi }}" target="_blank"
       class="btn-white inline-flex items-center gap-2 px-8 py-4 font-bold">
        <x-icon name="video" class="w-5 h-5" /> Abrir Videollamada
    </a>
</div>
<div class="bg-white rounded-lg shadow-md overflow-hidden" style="height: 85vh;">
    <div class="bg-blue-600 text-white p-3 flex justify-between items-center">
        <div>
            <h1 class="font-bold">{{ $clase->materia->nombre }} - {{ $clase->fecha->format('d/m/Y') }} {{ substr($clase->hora_inicio, 0, 5) }}</h1>
            <p class="text-sm">{{ __('ui.room') }}: {{ $clase->sala_jitsi }}</p>
        </div>
        <a href="{{ Auth::user()->rol === 'tutor' ? route('tutor.dashboard') : route('estudiante.dashboard') }}"
           class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">
            {{ __('ui.sign_out_short') }}
        </a>
    </div>
    
    <iframe 
    src="https://meet.jit.si/{{ $clase->sala_jitsi }}"
    allow="camera; microphone; fullscreen; display-capture; autoplay"
    style="width: 100%; height: calc(100% - 70px); border: 0;">
</iframe>
</div>
@endsection
