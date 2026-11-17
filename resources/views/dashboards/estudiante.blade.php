@extends('layouts.app')

@section('title', __('ui.student_dashboard'))

@section('content')

<div class="bg-white p-6 rounded-lg shadow-md mb-6"> <div class="flex justify-between items-center"> <div> <h1 class="text-3xl font-bold text-gray-800">{{ __('ui.student_dashboard') }}</h1> <p class="text-gray-600">{{ __('ui.welcome_user', ['name' => Auth::user()->nombre]) }}</p> </div>
    <a href="{{ route('busqueda.index') }}"
       class="inline-flex items-center gap-2 bg-orange-600 text-white px-6 py-3 rounded-xl hover:bg-orange-700 font-bold">
        <x-icon name="search" class="w-5 h-5" /> {{ __('ui.search_tutors') }}
    </a>
</div>

</div> <div class="bg-white p-6 rounded-lg shadow-md"> <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ __('ui.my_classes') }}</h2>
@if($clases->isEmpty())

    <div class="text-center py-8">
        <p class="text-gray-500 mb-4">
            {{ __('ui.no_classes_booked') }}
        </p>

        <a href="{{ route('busqueda.index') }}"
           class="inline-flex items-center gap-2 bg-orange-600 text-white px-6 py-2.5 rounded-xl hover:bg-orange-700">
            <x-icon name="search" class="w-4 h-4" /> {{ __('ui.search_my_first_tutor') }}
        </a>
    </div>

@else

    <div class="overflow-x-auto">
        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">{{ __('ui.tutors') }}</th>
                    <th class="p-3 text-left">{{ __('ui.subject') }}</th>
                    <th class="p-3 text-left">{{ __('ui.class_date_time') }}</th>
                    <th class="p-3 text-left">{{ __('ui.total') }}</th>
                    <th class="p-3 text-left">{{ __('ui.status') }}</th>
                    <th class="p-3 text-left">{{ __('ui.actions') }}</th>
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
                                        __('ui.payment_status_pending'),
                                        'bg-yellow-100 text-yellow-800'
                                    ],
                                    'pendiente' => [
                                        __('ui.waiting_validation'),
                                        'bg-blue-100 text-blue-800'
                                    ],
                                    'aceptada' => [
                                        __('ui.accepted'),
                                        'bg-green-100 text-green-800'
                                    ],
                                    'rechazada' => [
                                        __('ui.rejected'),
                                        'bg-red-100 text-red-800'
                                    ],
                                    'cancelada' => [
                                        __('ui.cancelled'),
                                        'bg-gray-100 text-gray-800'
                                    ],
                                    'completada' => [
                                        __('ui.completed'),
                                        'bg-purple-100 text-purple-800'
                                    ],
                                ];

                                [$texto, $colorBadge] =
                                    $estados[$clase->estado]
                                    ?? [__('ui.unknown'), 'bg-gray-100 text-gray-800'];
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
                                       class="inline-flex items-center gap-1 hover:underline text-xs">
                                        <x-icon name="credit-card" class="w-3.5 h-3.5" /> {{ __('ui.pay') }}
                                    </a>

                                {{-- Entrar a clase --}}
                                @elseif($clase->estado === 'aceptada')

                                    <a href="{{ route('clases.sala', $clase->id) }}"
                                       class="inline-flex items-center gap-1 hover:underline text-xs font-bold">
                                        <x-icon name="video" class="w-3.5 h-3.5" /> {{ __('ui.enter_class') }}
                                    </a>

                                {{-- Reseñar --}}
                                @elseif($clase->estado === 'completada' && !$clase->resena)

                                    <a href="{{ route('resenas.create', $clase->id) }}"
                                       class="inline-flex items-center gap-1 hover:underline text-xs">
                                        <x-icon name="star" class="w-3.5 h-3.5" /> {{ __('ui.write_review') }}
                                    </a>

                                @endif


                                {{-- Comprobante PDF --}}
                                @if(
                                    in_array($clase->estado, ['pendiente', 'aceptada', 'completada'])
                                    && $clase->pago
                                    && $clase->pago->estado === 'pagado'
                                )

                                    <a href="{{ route('pagos.comprobante', $clase->id) }}"
                                       class="inline-flex items-center gap-1 bg-white/10 hover:bg-white/20 border border-white/15 px-3 py-1.5 rounded-lg text-xs font-medium transition"
                                       title="{{ __('ui.download_receipt') }}">
                                        <x-icon name="file-text" class="w-3.5 h-3.5" /> PDF
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