@extends('layouts.app')

@section('title', 'Dashboard Tutor')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-gray-800 mb-4">Panel de Tutor</h1>
    <p class="text-gray-600">Bienvenido, {{ Auth::user()->nombre }}.</p>

    @if(!Auth::user()->tutor)
        <div class="mt-6 p-6 bg-blue-50 border-l-4 border-blue-500 rounded">
            <h2 class="text-xl font-semibold text-blue-800 mb-2">Completa tu perfil de tutor</h2>
            <p class="text-blue-700 mb-4">Para empezar a recibir solicitudes de clases, necesitas completar tu perfil y subir tus documentos.</p>
            <a href="{{ route('tutor.register.form') }}"
               class="inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                Completar Perfil
            </a>
        </div>
    @elseif(Auth::user()->tutor->aprobacion === 'pendiente')
        <div class="mt-6 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700">
            <p><strong>⏳ Perfil en revisión:</strong> Tu solicitud está siendo evaluada por el administrador.</p>
        </div>
    @elseif(Auth::user()->tutor->aprobacion === 'rechazado')
        <div class="mt-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
            <p><strong>❌ Perfil rechazado:</strong> {{ Auth::user()->tutor->motivo_rechazo }}</p>
            <a href="{{ route('tutor.register.form') }}" class="text-red-800 underline mt-2 inline-block">Volver a intentar</a>
        </div>
    @else
        <div class="mt-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
            <p><strong>✅ Perfil aprobado:</strong> Ya puedes recibir solicitudes de clases.</p>
        </div>
    @endif
</div>
@endsection
