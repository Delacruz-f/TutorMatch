@extends('layouts.app')

@section('title', 'Panel del Tutor')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Panel del Tutor</h1>
            <p class="text-gray-600">Bienvenido, {{ Auth::user()->nombre }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-500">Saldo disponible</p>
            <p class="text-3xl font-bold text-green-600">S/ {{ $tutor->saldoDisponible() }}</p>
            <a href="{{ route('tutor.retiros.index') }}" class="text-sm text-blue-600 hover:underline">Solicitar retiro →</a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

{{-- Solicitudes Pendientes --}}
<div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">📩 Solicitudes Pendientes ({{ $solicitudes->count() }})</h2>

    @if($solicitudes->isEmpty())
        <p class="text-gray-500">No tienes solicitudes pendientes.</p>
    @else
        <div class="space-y-3">
            @foreach($solicitudes as $s)
                <div class="border rounded-lg p-4 bg-yellow-50">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-bold">{{ $s->estudiante->nombre }} {{ $s->estudiante->apellido }}</p>
                            <p class="text-sm">Materia: <strong>{{ $s->materia->nombre }}</strong></p>
                            <p class="text-sm">{{ $s->fecha->format('d/m/Y') }} de {{ substr($s->hora_inicio, 0, 5) }} a {{ substr($s->hora_fin, 0, 5) }}</p>
                            <p class="text-sm text-green-600 font-bold">Ganarás: S/ {{ $s->monto_tutor }}</p>
                        </div>
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('tutor.clases.aceptar', $s->id) }}">
                                @csrf
                                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                    ✅ Aceptar
                                </button>
                            </form>
                            <form method="POST" action="{{ route('tutor.clases.rechazar', $s->id) }}">
                                @csrf
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                    ❌ Rechazar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Próximas Clases --}}
<div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">📅 Próximas Clases</h2>

    @if($proximas->isEmpty())
        <p class="text-gray-500">No tienes clases próximas.</p>
    @else
        <div class="space-y-3">
            @foreach($proximas as $c)
                <div class="border rounded-lg p-4 bg-blue-50">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-bold">{{ $c->estudiante->nombre }} - {{ $c->materia->nombre }}</p>
                            <p class="text-sm">{{ $c->fecha->format('d/m/Y') }} {{ substr($c->hora_inicio, 0, 5) }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('clases.sala', $c->id) }}"
                               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                                📹 Sala Jitsi
                            </a>
                            <form method="POST" action="{{ route('tutor.clases.completar', $c->id) }}">
                                @csrf
                                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 text-sm">
                                    🏁 Completar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Historial --}}
<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">📜 Historial</h2>
    @if($historial->isEmpty())
        <p class="text-gray-500">Sin historial aún.</p>
    @else
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Fecha</th>
                    <th class="p-2 text-left">Estudiante</th>
                    <th class="p-2 text-left">Materia</th>
                    <th class="p-2 text-left">Ganancia</th>
                    <th class="p-2 text-left">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historial as $h)
                    <tr class="border-b">
                        <td class="p-2">{{ $h->fecha->format('d/m/Y') }}</td>
                        <td class="p-2">{{ $h->estudiante->nombre }}</td>
                        <td class="p-2">{{ $h->materia->nombre }}</td>
                        <td class="p-2">S/ {{ $h->monto_tutor }}</td>
                        <td class="p-2">
                            <span class="text-xs px-2 py-1 rounded bg-gray-100">{{ $h->estado }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
