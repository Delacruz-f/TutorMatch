@extends('layouts.app')

@section('title', __('ui.withdrawals'))

@section('content')
<div class="glass rounded-2xl p-4 sm:p-6 text-white mb-6">
    <h1 class="text-2xl sm:text-3xl font-bold flex items-center"><x-icon name="wallet" class="w-7 h-7" /> {{ __('ui.withdrawals') }}</h1>
    <p class="text-2xl font-bold text-green-300 mt-2">Saldo disponible: S/ {{ $saldo }}</p>
</div>

<div class="glass rounded-2xl p-4 sm:p-6 text-white mb-6">
    <h2 class="text-xl sm:text-2xl font-bold mb-4">{{ __('ui.request_withdrawal_title') }}</h2>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('tutor.retiros.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @csrf
        <input type="number" name="monto" step="0.01" min="10" max="{{ $saldo }}" placeholder="{{ __('ui.amount_currency') }}" required
               class="px-3 py-2 border rounded-md">
        <select name="metodo" required class="px-3 py-2 border rounded-md">
            <option value="yape">{{ __('ui.method_labels.yape') }}</option>
            <option value="plin">{{ __('ui.method_labels.plin') }}</option>
        </select>
        <input type="text" name="numero_cuenta" placeholder="{{ __('ui.mobile_number') }}" required
               class="px-3 py-2 border rounded-md">
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            {{ __('ui.request_withdrawal_title') }}
        </button>
    </form>
</div>

<div class="glass rounded-2xl p-4 sm:p-6 text-white">
    <h2 class="text-xl sm:text-2xl font-bold mb-4">{{ __('ui.withdrawal_history') }}</h2>
    @if($retiros->isEmpty())
        <p class="text-white/70">{{ __('ui.no_withdrawals_yet') }}</p>
    @else
        <div class="overflow-x-auto">
        <table class="w-full min-w-[36rem] text-sm">
            <thead>
                <tr class="border-b border-white/20 text-white/80">
                    <th class="p-3 text-left">{{ __('ui.date') }}</th>
                    <th class="p-3 text-left">{{ __('ui.amount') }}</th>
                    <th class="p-3 text-left">{{ __('ui.payment_method') }}</th>
                    <th class="p-3 text-left">{{ __('ui.account_number') }}</th>
                    <th class="p-3 text-left">{{ __('ui.status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($retiros as $r)
                    <tr class="border-b border-white/10 hover:bg-white/5">
                        <td class="whitespace-nowrap p-3">{{ $r->created_at->format('d/m/Y') }}</td>
                        <td class="whitespace-nowrap p-3">S/ {{ $r->monto }}</td>
                        <td class="p-3">{{ strtoupper($r->metodo) }}</td>
                        <td class="p-3">{{ $r->numero_cuenta }}</td>
                        <td class="p-3">
                            <span class="whitespace-nowrap text-xs px-2 py-1 rounded
                                {{ $r->estado === 'pagado' ? 'bg-green-400/20 text-green-100' : '' }}
                                {{ $r->estado === 'pendiente' ? 'bg-yellow-400/20 text-yellow-100' : '' }}
                                {{ $r->estado === 'rechazado' ? 'bg-red-400/20 text-red-100' : '' }}">
                                {{ __('ui.status_labels.' . $r->estado) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endif
</div>
@endsection
