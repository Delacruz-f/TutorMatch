@extends('layouts.app')

@section('title', 'Reservar Clase')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Reservar Clase</h1>

    <div class="bg-blue-50 p-4 rounded-lg mb-6">
        <p class="font-bold text-blue-800">
            Tutor: {{ $tutor->user->nombre }} {{ $tutor->user->apellido }}
        </p>
        <p class="text-blue-700">Precio: S/ {{ $tutor->precio_hora }} / hora</p>
        <p class="text-blue-700">Comisión plataforma: {{ $comision }}%</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('reservas.store') }}">
        @csrf
        <input type="hidden" name="tutor_id" value="{{ $tutor->id }}">

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Materia</label>
            <select name="materia_id" required class="w-full px-3 py-2 border rounded-md">
                @foreach($tutor->materias as $m)
                    <option value="{{ $m->id }}" {{ $materiaSeleccionada && $materiaSeleccionada->id == $m->id ? 'selected' : '' }}>
                        {{ $m->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Fecha</label>
            <input type="date" name="fecha" required id="input-fecha"
       value="{{ old('fecha') }}"
       class="w-full px-3 py-2 border rounded-md">

<script>
    // Asignar la fecha mínima desde JS (usa la fecha del navegador)
    document.addEventListener('DOMContentLoaded', function() {
        const hoy = new Date();
        const yyyy = hoy.getFullYear();
        const mm = String(hoy.getMonth() + 1).padStart(2, '0');
        const dd = String(hoy.getDate()).padStart(2, '0');
        document.getElementById('input-fecha').min = `${yyyy}-${mm}-${dd}`;
    });
</script>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Hora de inicio</label>
                <input type="time" name="hora_inicio" required value="{{ old('hora_inicio') }}"
                       class="w-full px-3 py-2 border rounded-md">
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Duración</label>
                <select name="duracion_min" required class="w-full px-3 py-2 border rounded-md">
                    <option value="60">1 hora</option>
                    <option value="90">1.5 horas</option>
                    <option value="120">2 horas</option>
                </select>
            </div>
        </div>

        <div class="bg-yellow-50 p-4 rounded-lg mb-6">
            <p class="text-sm text-yellow-800">
                ℹ️ El precio final se calculará automáticamente según la duración y el precio/hora del tutor.
            </p>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-md hover:bg-blue-700 font-bold">
            Continuar al Pago
        </button>
    </form>
</div>
@endsection
