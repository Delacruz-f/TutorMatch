@extends('layouts.app')

@section('title', __('ui.manage_withdrawals'))

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 flex items-center"><x-icon name="wallet" class="w-7 h-7" /> {{ __('ui.manage_withdrawals') }}</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <h2 class="text-2xl font-semibold mb-4 text-yellow-600">⏳ Pendientes ({{ $pendientes->count() }})</h2>

    @if($pendientes->isEmpty())
        <p class="text-gray-500 mb-8">{{ __('ui.no_pending_withdrawals') }}</p>
    @else
        <div class="space-y-3 mb-8">
            @foreach($pendientes as $r)
                <div class="border rounded-lg p-4 bg-yellow-50">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">{{ __('ui.tutor') }}</p>
                            <p class="font-bold">{{ $r->tutor->user->nombre }} {{ $r->tutor->user->apellido }}</p>
                            <p class="text-sm">{{ $r->tutor->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">{{ __('ui.method_account') }}</p>
                            <p class="font-bold">{{ strtoupper($r->metodo) }}: {{ $r->numero_cuenta }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">{{ __('ui.amount') }}</p>
                            <p class="text-2xl font-bold text-green-600">S/ {{ $r->monto }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2 mt-3">
                        <form method="POST" action="{{ route('admin.retiros.pagar', $r->id) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 bg-green-600 text-white px-4 py-2 rounded-xl font-semibold hover:bg-green-700">
                                <x-icon name="check" class="w-4 h-4" /> {{ __('ui.mark_as_paid') }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.retiros.rechazar', $r->id) }}"
                              onsubmit="return confirm(@json(__('ui.reject_withdrawal_confirm')))" class="flex gap-2">
                            @csrf
                            <input type="text" name="motivo" placeholder="{{ __('ui.reason_short') }}" required
                                   class="px-3 py-1 border rounded text-sm">
                            <button type="submit" class="inline-flex items-center gap-1.5 bg-red-600 text-white px-4 py-2 rounded-xl font-semibold hover:bg-red-700">
                                <x-icon name="x" class="w-4 h-4" /> Rechazar
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <h2 class="text-2xl font-semibold mb-4 text-green-600">{{ __('ui.history') }}</h2>
    @if($historial->isEmpty())
        <p class="text-gray-500">{{ __('ui.no_history') }}</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full min-w-[36rem] text-sm">
                <thead>
                    <tr class="border-b border-white/20 text-white/80">
                        <th class="p-3 text-left">{{ __('ui.date') }}</th>
                        <th class="p-3 text-left">{{ __('ui.tutor') }}</th>
                        <th class="p-3 text-left">{{ __('ui.amount') }}</th>
                        <th class="p-3 text-left">{{ __('ui.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($historial as $h)
                        <tr class="border-b border-white/10 hover:bg-white/5">
                            <td class="whitespace-nowrap p-3">{{ $h->updated_at->format('d/m/Y') }}</td>
                            <td class="p-3">{{ $h->tutor->user->nombre }}</td>
                            <td class="whitespace-nowrap p-3">S/ {{ $h->monto }}</td>
                            <td class="p-3">
                                <span class="whitespace-nowrap rounded px-2 py-1 text-xs
                                    {{ $h->estado === 'pagado' ? 'bg-green-400/20 text-green-100' : 'bg-red-400/20 text-red-100' }}">
                                    {{ __('ui.status_labels.' . $h->estado) }}
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
