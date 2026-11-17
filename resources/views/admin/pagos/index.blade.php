@extends('layouts.app')

@section('title', __('ui.payment_validation'))

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 flex items-center"><x-icon name="credit-card" class="w-7 h-7" /> {{ __('ui.payment_validation') }}</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <h2 class="text-2xl font-semibold mb-4 text-yellow-600">⏳ {{ __('ui.pending_validation_count', ['count' => $pagosPendientes->count()]) }}</h2>

    @if($pagosPendientes->isEmpty())
        <p class="text-gray-500 mb-8">{{ __('ui.no_payments_pending') }}</p>
    @else
        <div class="space-y-4 mb-8">
            @foreach($pagosPendientes as $pago)
                <div class="border rounded-lg p-4 bg-yellow-50">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">{{ __('ui.student') }}</p>
                            <p class="font-bold">{{ $pago->user->nombre }} {{ $pago->user->apellido }}</p>
                            <p class="text-sm">{{ $pago->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">{{ __('ui.tutor_or_subject') }}</p>
                            <p class="font-bold">{{ $pago->clase->tutor->user->nombre }} {{ $pago->clase->tutor->user->apellido }}</p>
                            <p class="text-sm">{{ $pago->clase->materia->nombre }}</p>
                            <p class="text-sm">{{ $pago->clase->fecha->format('d/m/Y') }} {{ substr($pago->clase->hora_inicio, 0, 5) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">{{ __('ui.payment') }}</p>
                            <p class="font-bold text-green-600 text-lg">S/ {{ $pago->monto }}</p>
                            <p class="text-sm">{{ __('ui.payment_method_short') }} {{ strtoupper($pago->metodo) }}</p>
                            <p class="text-sm">{{ __('ui.reference_short') }} {{ $pago->referencia }}</p>
                        </div>
                    </div>
                    <div class="mt-3 flex gap-3 items-center">
                        <a href="{{ Storage::url($pago->comprobante_imagen) }}" target="_blank"
                           class="bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600">
                            {{ __('ui.view_receipt') }}
                        </a>
                        <form method="POST" action="{{ route('admin.pagos.aprobar', $pago->id) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 bg-green-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-green-700">
                                <x-icon name="check" class="w-4 h-4" /> {{ __('ui.approve') }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.pagos.rechazar', $pago->id) }}"
                              onsubmit="return confirm(@json(__('ui.capture_payment_confirm')))" class="flex gap-2">
                            @csrf
                            <input type="text" name="motivo" placeholder="{{ __('ui.reject_reason') }}" required
                                   class="px-3 py-1 border rounded text-sm">
                            <button type="submit" class="inline-flex items-center gap-1.5 bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-red-700">
                                <x-icon name="x" class="w-4 h-4" /> {{ __('ui.reject') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <h2 class="text-2xl font-semibold mb-4 text-green-600 flex items-center"><x-icon name="check-circle" class="w-6 h-6" /> {{ __('ui.processed_recently') }}</h2>
    @if($pagosRecientes->isEmpty())
        <p class="text-gray-500">{{ __('ui.no_processed_payments') }}</p>
    @else
        <table class="w-full text-sm border-collapse">
            <thead class="text-gray-500">
                <tr>
                    <th class="p-2 text-left">{{ __('ui.date') }}</th>
                    <th class="p-2 text-left">{{ __('ui.student') }}</th>
                    <th class="p-2 text-left">{{ __('ui.amount') }}</th>
                    <th class="p-2 text-left">{{ __('ui.status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pagosRecientes as $p)
                    <tr class="border-b">
                        <td class="p-2">{{ $p->updated_at->format('d/m/Y H:i') }}</td>
                        <td class="p-2">{{ $p->user->nombre }} {{ $p->user->apellido }}</td>
                        <td class="p-2">S/ {{ $p->monto }}</td>
                        <td class="p-2">
                            @php
                                $colores = [
                                    'pagado' => 'bg-green-100 text-green-800',
                                    'fallido' => 'bg-red-100 text-red-800',
                                    'reembolsado' => 'bg-gray-100 text-gray-800',
                                ];
                                $color = $colores[$p->estado] ?? 'bg-gray-100';
                            @endphp
                            <span class="{{ $color }} px-2 py-1 rounded text-xs">{{ __('ui.status_labels.' . $p->estado) }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
