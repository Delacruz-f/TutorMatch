@extends('layouts.app')

@section('title', __('ui.payment_history'))

@section('content')
<div class="glass rounded-2xl p-4 sm:p-6 text-white mb-6">
    <h1 class="text-2xl sm:text-3xl font-bold">{{ __('ui.payment_history') }}</h1>
</div>

<div class="glass rounded-2xl p-4 sm:p-6 text-white">
    @if($pagos->isEmpty())
        <p class="py-8 text-center text-white/70">{{ __('ui.no_processed_payments') }}</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full min-w-[64rem] text-sm">
                <thead>
                    <tr class="border-b border-white/20 text-white/80">
                        <th class="p-3 text-left">{{ __('ui.date') }}</th>
                        <th class="p-3 text-left">{{ __('ui.subject') }}</th>
                        <th class="p-3 text-left">{{ __('ui.tutors') }}</th>
                        <th class="p-3 text-left">{{ __('ui.payment_method') }}</th>
                        <th class="p-3 text-left">{{ __('ui.amount') }}</th>
                        <th class="p-3 text-left">{{ __('ui.status') }}</th>
                        <th class="p-3 text-left">{{ __('ui.reference') }}</th>
                        <th class="p-3 text-left">{{ __('ui.view_receipt') }}</th>
                        <th class="p-3 text-left">{{ __('ui.my_classes') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagos as $pago)
                        <tr class="border-b border-white/10 align-top hover:bg-white/5">
                            <td class="whitespace-nowrap p-3">
                                {{ ($pago->pagado_en ?? $pago->created_at)->format('d/m/Y H:i') }}
                            </td>
                            <td class="p-3">{{ $pago->clase->materia->nombre }}</td>
                            <td class="p-3">{{ $pago->clase->tutor->user->nombre }} {{ $pago->clase->tutor->user->apellido }}</td>
                            <td class="p-3">{{ __('ui.method_labels.' . $pago->metodo) }}</td>
                            <td class="whitespace-nowrap p-3">S/ {{ $pago->monto }}</td>
                            <td class="p-3">
                                @php
                                    $colores = [
                                        'pendiente' => 'bg-yellow-400/20 text-yellow-100',
                                        'pagado' => 'bg-green-400/20 text-green-100',
                                        'fallido' => 'bg-red-400/20 text-red-100',
                                        'reembolsado' => 'bg-gray-400/20 text-gray-100',
                                    ];
                                @endphp
                                <span class="whitespace-nowrap rounded px-2 py-1 text-xs {{ $colores[$pago->estado] ?? 'bg-white/10 text-white/80' }}">
                                    {{ __('ui.status_labels.' . $pago->estado) }}
                                </span>
                            </td>
                            <td class="max-w-56 break-words p-3">{{ $pago->referencia }}</td>
                            <td class="p-3">
                                @if($pago->comprobante_imagen)
                                    <a href="{{ route('pagos.imagen', $pago->id) }}" target="_blank" rel="noopener"
                                       class="inline-flex flex-col items-center gap-2 text-orange-200 hover:text-orange-100">
                                        <img src="{{ route('pagos.imagen', $pago->id) }}" alt="{{ __('ui.view_receipt') }}"
                                             class="h-16 w-16 rounded-lg border border-white/20 object-cover">
                                        <span class="text-xs">{{ __('ui.view_receipt') }}</span>
                                    </a>
                                @else
                                    <span class="text-xs text-white/60">{{ __('ui.no_receipt_uploaded') }}</span>
                                @endif
                            </td>
                            <td class="p-3">
                                <a href="{{ route('estudiante.dashboard') }}#clase-{{ $pago->clase_id }}"
                                   class="text-orange-200 underline underline-offset-2 hover:text-orange-100">
                                    {{ __('ui.view_class') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $pagos->links() }}</div>
    @endif
</div>
@endsection
