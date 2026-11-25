@extends('layouts.app')

@section('title', __('ui.student_dashboard'))

@section('content')

<div class="glass rounded-2xl p-4 sm:p-6 text-white mb-6">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold">{{ __('ui.student_dashboard') }}</h1>
            <p class="text-white/75">{{ __('ui.welcome_user', ['name' => Auth::user()->nombre]) }}</p>
        </div>
    <a href="{{ route('busqueda.index') }}"
       class="inline-flex items-center gap-2 bg-orange-600 text-white px-6 py-3 rounded-xl hover:bg-orange-700 font-bold">
        <x-icon name="search" class="w-5 h-5" /> {{ __('ui.search_tutors') }}
    </a>
</div>

</div>

<x-flash type="success" />
<x-flash type="error" />

<div class="glass rounded-2xl p-4 sm:p-6 text-white">
    <h2 class="text-xl sm:text-2xl font-bold mb-4">{{ __('ui.my_classes') }}</h2>
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
        <table class="w-full text-sm text-white/90">

            <thead class="bg-white/10 text-white/75">
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

                    @php
                        $estadoCancelable = in_array($clase->estado, ['pendiente_pago', 'pendiente', 'aceptada'], true);
                        $inicioClase = \Carbon\Carbon::parse($clase->fecha->toDateString().' '.$clase->hora_inicio);
                        $puedeCancelar = $estadoCancelable && $inicioClase->greaterThanOrEqualTo(now()->addHours(24));
                        $puedeVerComprobante = in_array($clase->estado, ['pendiente', 'aceptada', 'completada'], true)
                            && $clase->pago
                            && $clase->pago->estado === 'pagado';
                        $tieneAccion = $clase->estado === 'pendiente_pago'
                            || $clase->estado === 'aceptada'
                            || ($clase->estado === 'completada' && ! $clase->resena)
                            || $estadoCancelable
                            || $puedeVerComprobante;
                    @endphp

                    <tr id="clase-{{ $clase->id }}" class="border-b border-white/10 hover:bg-white/5">

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

                            <span class="text-xs text-white/60">
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
                                       class="inline-flex items-center gap-1 rounded-lg bg-orange-500 px-2.5 py-1.5 text-xs font-semibold text-white hover:bg-orange-600">
                                        <x-icon name="credit-card" class="w-3.5 h-3.5" /> {{ __('ui.pay') }}
                                    </a>

                                {{-- Entrar a clase --}}
                                @elseif($clase->estado === 'aceptada')

                                    <a href="{{ route('clases.sala', $clase->id) }}"
                                       class="inline-flex items-center gap-1 text-xs font-bold text-green-200 hover:text-green-100">
                                        <x-icon name="video" class="w-3.5 h-3.5" /> {{ __('ui.enter_class') }}
                                    </a>

                                {{-- Reseñar --}}
                                @elseif($clase->estado === 'completada' && !$clase->resena)

                                    <a href="{{ route('resenas.create', $clase->id) }}"
                                       class="inline-flex items-center gap-1 text-xs text-orange-200 hover:text-orange-100">
                                        <x-icon name="star" class="w-3.5 h-3.5" /> {{ __('ui.write_review') }}
                                    </a>

                                @endif

                                @if($puedeCancelar)
                                    <form method="POST" action="{{ route('clases.cancelar', $clase->id) }}"
                                          onsubmit="return confirm(@json(__('ui.confirm_cancel_class')))">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg bg-red-500/80 px-2.5 py-1.5 text-xs font-semibold text-white hover:bg-red-600">
                                            <x-icon name="x" class="w-3.5 h-3.5" /> {{ __('ui.cancel_class') }}
                                        </button>
                                    </form>
                                @elseif($estadoCancelable)
                                    <span class="text-xs text-amber-200">{{ __('ui.cancel_24_hours_required') }}</span>
                                @endif


                                {{-- Comprobante PDF --}}
                                @if($puedeVerComprobante)

                                    <a href="{{ route('pagos.comprobante', $clase->id) }}"
                                       class="inline-flex items-center gap-1 bg-white/10 hover:bg-white/20 border border-white/15 px-3 py-1.5 rounded-lg text-xs font-medium transition"
                                       title="{{ __('ui.download_receipt') }}">
                                        <x-icon name="file-text" class="w-3.5 h-3.5" /> PDF
                                    </a>

                                @endif


                                {{-- Sin acciones --}}
                                @if(!$tieneAccion)

                                    <span class="text-white/50 text-xs">
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

</div>
@endsection