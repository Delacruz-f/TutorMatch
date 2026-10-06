@extends('layouts.app')

@section('title', 'Suscripciones')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <h1 class="text-3xl font-bold text-gray-800">⭐ Planes de Suscripción</h1>
    <p class="text-gray-600">Aparece primero en las búsquedas y consigue más alumnos.</p>
</div>

@if($suscripcionActiva)
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
        <p class="font-bold text-green-800">
            ✅ Tienes el plan <strong>{{ strtoupper($suscripcionActiva->plan) }}</strong> activo
        </p>
        <p class="text-sm text-green-700">Vence el {{ $suscripcionActiva->fecha_fin->format('d/m/Y') }}</p>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Plan Destacado -->
        <div class="bg-white p-6 rounded-lg shadow-md border-2 border-yellow-400">
            <h2 class="text-2xl font-bold text-yellow-600 mb-2">⭐ Plan Destacado</h2>
            <p class="text-3xl font-bold mb-4">S/ {{ $precioDestacado }}<span class="text-sm text-gray-500">/mes</span></p>
            <ul class="space-y-2 mb-6 text-sm text-gray-600">
                <li>✅ Apareces primero en búsquedas</li>
                <li>✅ Badge de "Destacado" en tu perfil</li>
                <li>✅ Mayor visibilidad</li>
            </ul>

            <form method="POST" action="{{ route('tutor.suscripciones.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="plan" value="destacado">
                <select name="metodo" class="w-full px-3 py-2 border rounded mb-2">
                    <option value="yape">Yape</option>
                    <option value="plin">Plin</option>
                </select>
                <input type="text" name="referencia" placeholder="N° de operación" required
                       class="w-full px-3 py-2 border rounded mb-2">
                <input type="file" name="comprobante_imagen" accept="image/*" required
                       class="w-full px-3 py-2 border rounded mb-2">
                <button type="submit" class="w-full bg-yellow-500 text-white py-2 rounded hover:bg-yellow-600 font-bold">
                    Contratar Destacado
                </button>
            </form>
        </div>

        <!-- Plan Premium -->
        <div class="bg-white p-6 rounded-lg shadow-md border-2 border-purple-500">
            <h2 class="text-2xl font-bold text-purple-600 mb-2">👑 Plan Premium</h2>
            <p class="text-3xl font-bold mb-4">S/ {{ $precioPremium }}<span class="text-sm text-gray-500">/mes</span></p>
            <ul class="space-y-2 mb-6 text-sm text-gray-600">
                <li>✅ Todo lo del plan Destacado</li>
                <li>✅ Publicidad en la página principal</li>
                <li>✅ Prioridad máxima en búsquedas</li>
            </ul>

            <form method="POST" action="{{ route('tutor.suscripciones.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="plan" value="premium">
                <select name="metodo" class="w-full px-3 py-2 border rounded mb-2">
                    <option value="yape">Yape</option>
                    <option value="plin">Plin</option>
                </select>
                <input type="text" name="referencia" placeholder="N° de operación" required
                       class="w-full px-3 py-2 border rounded mb-2">
                <input type="file" name="comprobante_imagen" accept="image/*" required
                       class="w-full px-3 py-2 border rounded mb-2">
                <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded hover:bg-purple-700 font-bold">
                    Contratar Premium
                </button>
            </form>
        </div>
    </div>
@endif

<!-- Historial -->
<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Historial de Suscripciones</h2>
    @if($historial->isEmpty())
        <p class="text-gray-500">Sin suscripciones anteriores.</p>
    @else
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Plan</th>
                    <th class="p-2 text-left">Inicio</th>
                    <th class="p-2 text-left">Fin</th>
                    <th class="p-2 text-left">Monto</th>
                    <th class="p-2 text-left">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historial as $h)
                    <tr class="border-b">
                        <td class="p-2">{{ ucfirst($h->plan) }}</td>
                        <td class="p-2">{{ $h->fecha_inicio->format('d/m/Y') }}</td>
                        <td class="p-2">{{ $h->fecha_fin->format('d/m/Y') }}</td>
                        <td class="p-2">S/ {{ $h->monto }}</td>
                        <td class="p-2">{{ ucfirst($h->estado) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
