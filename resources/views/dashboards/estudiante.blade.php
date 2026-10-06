@extends('layouts.app')

@section('title', 'Dashboard Estudiante')

@section('content')

<div class="bg-white p-6 rounded-lg shadow-md mb-6"> <div class="flex justify-between items-center"> <div> <h1 class="text-3xl font-bold text-gray-800">Panel de Estudiante</h1> <p class="text-gray-600">Bienvenido, {{ Auth::user()->nombre }}</p> </div>
    <a href="{{ route('busqueda.index') }}"
       class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 font-bold">
        🔍 Buscar Tutores
    </a>
</div>

</div> <div class="bg-white p-6 rounded-lg shadow-md"> <h2 class="text-2xl font-bold text-gray-800 mb-4">Mis Clases</h2>
@if($clases->isEmpty())

    <div class="text-center py-8">
        <p class="text-gray-500 mb-4">
            Aún no has reservado ninguna clase.
        </p>

        <a href="{{ route('busqueda.index') }}"
           class="inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
            Buscar mi primer tutor
        </a>
    </div>

@else

    <div class="overflow-x-auto">
        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Tutor</th>
                    <th class="p-3 text-left">Materia</th>
                    <th class="p-3 text-left">Fecha/Hora</th>
                    <th class="p-3 text-left">Total</th>
                    <th class="p-3 text-left">Estado</th>
                    <th class="p-3 text-left">Acciones</th>
                </tr>
            </thead>

            <tbody>

                @foreach($clases as $clase)

                    <tr class="border-b hover:bg-gray-50">

                        {{-- Tutor --}}
                        <td class="p-3">
                            {{ $clase->tutor->user->nombre }}
                            {{ $clase->tutor->user->apellido }}
                        </td>

                        {{-- Materia --}}
                        <td class="p-3">
                            {{ $clase->materia->nombre }}
                        </td>

                        {{-- Fecha y hora --}}
                        <td class="p-3">
                            {{ $clase->fecha->format('d/m/Y') }}<br>

                            <span class="text-xs text-gray-500">
                                {{ substr($clase->hora_inicio, 0, 5) }}
                            </span>
                        </td>

                        {{-- Total --}}
                        <td class="p-3">
                            S/ {{ $clase->precio_total }}
                        </td>

                        {{-- Estado --}}
                        <td class="p-3">

                            @php
                                $estados = [
                                    'pendiente_pago' => [
                                        '⏳ Pendiente de pago',
                                        'bg-yellow-100 text-yellow-800'
                                    ],
                                    'pendiente' => [
                                        '🕐 Esperando validación',
                                        'bg-blue-100 text-blue-800'
                                    ],
                                    'aceptada' => [
                                        '✅ Aceptada',
                                        'bg-green-100 text-green-800'
                                    ],
                                    'rechazada' => [
                                        '❌ Rechazada',
                                        'bg-red-100 text-red-800'
                                    ],
                                    'cancelada' => [
                                        '🚫 Cancelada',
                                        'bg-gray-100 text-gray-800'
                                    ],
                                    'completada' => [
                                        '🏁 Completada',
                                        'bg-purple-100 text-purple-800'
                                    ],
                                ];

                                [$texto, $colorBadge] =
                                    $estados[$clase->estado]
                                    ?? ['Desconocido', 'bg-gray-100 text-gray-800'];
                            @endphp

                            <span class="{{ $colorBadge }} text-xs px-2 py-1 rounded">
                                {{ $texto }}
                            </span>

                        </td>

                        {{-- Acciones --}}
                        <td class="p-3">

                            <div class="flex flex-wrap items-center gap-2">

                                {{-- Pagar --}}
                                @if($clase->estado === 'pendiente_pago')

                                    <a href="{{ route('reservas.pago', $clase->id) }}"
                                       class="text-blue-600 hover:underline text-xs">
                                        Pagar
                                    </a>

                                {{-- Entrar a clase --}}
                                @elseif($clase->estado === 'aceptada')

                                    <a href="{{ route('clases.sala', $clase->id) }}"
                                       class="text-green-600 hover:underline text-xs font-bold">
                                        📹 Entrar
                                    </a>

                                {{-- Reseñar --}}
                                @elseif($clase->estado === 'completada' && !$clase->resena)

                                    <a href="{{ route('resenas.create', $clase->id) }}"
                                       class="text-yellow-600 hover:underline text-xs">
                                        ⭐ Reseñar
                                    </a>

                                @endif


                                {{-- Comprobante PDF --}}
                                @if(
                                    in_array($clase->estado, ['pendiente', 'aceptada', 'completada'])
                                    && $clase->pago
                                    && $clase->pago->estado === 'pagado'
                                )

                                    <a href="{{ route('pagos.comprobante', $clase->id) }}"
                                       class="bg-purple-500/40 hover:bg-purple-500/60 px-3 py-1.5 rounded-lg text-xs font-medium transition"
                                       title="Descargar comprobante">
                                        📄 PDF
                                    </a>

                                @endif


                                {{-- Sin acciones --}}
                                @if(
                                    $clase->estado !== 'pendiente_pago'
                                    && $clase->estado !== 'aceptada'
                                    && !($clase->estado === 'completada' && !$clase->resena)
                                    && !(
                                        in_array($clase->estado, ['pendiente', 'aceptada', 'completada'])
                                        && $clase->pago
                                        && $clase->pago->estado === 'pagado'
                                    )
                                )

                                    <span class="text-gray-400 text-xs">
                                        -
                                    </span>

                                @endif

                            </div>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>
    </div>

@endif

</div> @endsection