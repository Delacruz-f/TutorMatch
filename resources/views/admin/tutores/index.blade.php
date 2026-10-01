@extends('layouts.app')

@section('title', 'Gestión de Tutores')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Gestión de Tutores</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tutores Pendientes -->
    <h2 class="text-2xl font-semibold mb-4 text-yellow-600">⏳ Pendientes de Aprobación ({{ $tutoresPendientes->count() }})</h2>

    @if($tutoresPendientes->isEmpty())
        <p class="text-gray-500 mb-6">No hay tutores pendientes.</p>
    @else
        <div class="space-y-4 mb-8">
            @foreach($tutoresPendientes as $tutor)
                <div class="border rounded-lg p-4 bg-yellow-50">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-lg">{{ $tutor->user->nombre }} {{ $tutor->user->apellido }}</h3>
                            <p class="text-sm text-gray-600">{{ $tutor->user->email }}</p>
                            <p class="text-sm mt-1"><strong>Universidad:</strong> {{ $tutor->universidad }}</p>
                            <p class="text-sm"><strong>Carrera:</strong> {{ $tutor->carrera }}</p>
                            <p class="text-sm"><strong>DNI:</strong> {{ $tutor->dni_numero }}</p>
                            <p class="text-sm"><strong>Precio/hora:</strong> S/ {{ $tutor->precio_hora }}</p>
                            <p class="text-sm mt-1"><strong>Materias:</strong>
                                {{ $tutor->materias->pluck('nombre')->join(', ') }}
                            </p>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('admin.tutores.documento', [$tutor->id, 'dni']) }}" target="_blank"
                               class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 text-center">
                                Ver DNI
                            </a>
                            <a href="{{ route('admin.tutores.documento', [$tutor->id, 'cv']) }}" target="_blank"
                               class="bg-purple-500 text-white px-3 py-1 rounded text-sm hover:bg-purple-600 text-center">
                                Ver CV
                            </a>
                        </div>
                    </div>
                    <div class="flex gap-2 mt-4">
                        <form method="POST" action="{{ route('admin.tutores.aprobar', $tutor->id) }}">
                            @csrf
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                ✅ Aprobar
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.tutores.rechazar', $tutor->id) }}"
                              onsubmit="return confirm('¿Rechazar este tutor?')">
                            @csrf
                            <input type="text" name="motivo_rechazo" placeholder="Motivo del rechazo" required
                                   class="px-3 py-2 border rounded mr-2">
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                ❌ Rechazar
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Tutores Aprobados Recientes -->
    <h2 class="text-2xl font-semibold mb-4 text-green-600">✅ Aprobados Recientemente</h2>
    @if($tutoresAprobados->isEmpty())
        <p class="text-gray-500">No hay tutores aprobados aún.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($tutoresAprobados as $tutor)
                <div class="border rounded-lg p-4 bg-green-50">
                    <h3 class="font-bold">{{ $tutor->user->nombre }} {{ $tutor->user->apellido }}</h3>
                    <p class="text-sm text-gray-600">{{ $tutor->carrera }} - {{ $tutor->universidad }}</p>
                    <p class="text-sm">S/ {{ $tutor->precio_hora }}/hora</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
