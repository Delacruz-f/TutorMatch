@extends('layouts.app')

@section('title', __('ui.subscriptions'))

@section('content')
<div class="bg-white p-4 sm:p-6 rounded-lg shadow-md mb-6">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 flex items-center"><x-icon name="sparkles" class="w-7 h-7 shrink-0" /> {{ __('ui.subscription_plans') }}</h1>
    <p class="text-gray-600">{{ __('ui.featured_search_benefit') }}</p>
</div>

@if($suscripcionActiva)
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
        <p class="font-bold text-green-800 inline-flex items-center gap-2">
            <x-icon name="check-circle" class="w-5 h-5" /> {{ __('ui.active_plan_notice', ['plan' => __('ui.plan_labels.' . $suscripcionActiva->plan)]) }}
        </p>
        <p class="text-sm text-green-700">{{ __('ui.expires_on', ['date' => $suscripcionActiva->fecha_fin->format('d/m/Y')]) }}</p>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Plan Destacado -->
        <div class="min-w-0 bg-white p-4 sm:p-6 rounded-lg shadow-md border-2 border-yellow-400">
            <h2 class="text-xl sm:text-2xl font-bold text-yellow-600 mb-2 flex items-center"><x-icon name="star" class="w-6 h-6 shrink-0" /> {{ __('ui.featured_plan') }}</h2>
            <p class="text-3xl font-bold mb-4">S/ {{ $precioDestacado }}<span class="text-sm text-gray-500">{{ __('ui.per_month') }}</span></p>
            <ul class="space-y-2 mb-6 text-sm text-gray-600">
                <li class="flex items-center gap-2"><x-icon name="check" class="w-4 h-4" /> {{ __('ui.first_in_searches') }}</li>
                <li class="flex items-center gap-2"><x-icon name="check" class="w-4 h-4" /> {{ __('ui.featured_badge_profile') }}</li>
                <li class="flex items-center gap-2"><x-icon name="check" class="w-4 h-4" /> {{ __('ui.greater_visibility') }}</li>
            </ul>

            <form method="POST" action="{{ route('tutor.suscripciones.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="plan" value="destacado">
                <select name="metodo" class="mb-2 w-full min-w-0 rounded border px-3 py-2">
                    <option value="yape">{{ __('ui.method_labels.yape') }}</option>
                    <option value="plin">{{ __('ui.method_labels.plin') }}</option>
                </select>
                <input type="text" name="referencia" placeholder="{{ __('ui.operation_number') }}" required
                       class="mb-2 w-full min-w-0 rounded border px-3 py-2">
                <input type="file" name="comprobante_imagen" accept="image/*" required
                       class="mb-2 block w-full min-w-0 max-w-full rounded border px-3 py-2 text-sm">
                <button type="submit" class="w-full bg-yellow-500 text-white py-2 rounded hover:bg-yellow-600 font-bold">
                    {{ __('ui.hire_featured') }}
                </button>
            </form>
        </div>

        <!-- Plan Premium -->
        <div class="min-w-0 bg-white p-4 sm:p-6 rounded-lg shadow-md border-2 border-purple-500">
            <h2 class="text-xl sm:text-2xl font-bold text-purple-600 mb-2 flex items-center"><x-icon name="crown" class="w-6 h-6 shrink-0" /> {{ __('ui.premium_plan') }}</h2>
            <p class="text-3xl font-bold mb-4">S/ {{ $precioPremium }}<span class="text-sm text-gray-500">{{ __('ui.per_month') }}</span></p>
            <ul class="space-y-2 mb-6 text-sm text-gray-600">
                <li class="flex items-center gap-2"><x-icon name="check" class="w-4 h-4" /> {{ __('ui.everything_featured') }}</li>
                <li class="flex items-center gap-2"><x-icon name="check" class="w-4 h-4" /> {{ __('ui.homepage_advertising') }}</li>
                <li class="flex items-center gap-2"><x-icon name="check" class="w-4 h-4" /> {{ __('ui.maximum_search_priority') }}</li>
            </ul>

            <form method="POST" action="{{ route('tutor.suscripciones.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="plan" value="premium">
                <select name="metodo" class="mb-2 w-full min-w-0 rounded border px-3 py-2">
                    <option value="yape">{{ __('ui.method_labels.yape') }}</option>
                    <option value="plin">{{ __('ui.method_labels.plin') }}</option>
                </select>
                <input type="text" name="referencia" placeholder="{{ __('ui.operation_number') }}" required
                       class="mb-2 w-full min-w-0 rounded border px-3 py-2">
                <input type="file" name="comprobante_imagen" accept="image/*" required
                       class="mb-2 block w-full min-w-0 max-w-full rounded border px-3 py-2 text-sm">
                <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded hover:bg-purple-700 font-bold">
                    {{ __('ui.hire_premium') }}
                </button>
            </form>
        </div>
    </div>
@endif

<!-- Historial -->
<div class="glass rounded-2xl p-4 sm:p-6 text-white">
    <h2 class="text-xl sm:text-2xl font-bold mb-4">{{ __('ui.subscription_history') }}</h2>
    @if($historial->isEmpty())
        <p class="text-white/70">{{ __('ui.no_previous_subscriptions') }}</p>
    @else
        <div class="overflow-x-auto">
        <table class="w-full min-w-[36rem] text-sm">
            <thead>
                <tr class="border-b border-white/20 text-white/80">
                    <th class="p-3 text-left">{{ __('ui.plan') }}</th>
                    <th class="p-3 text-left">{{ __('ui.home') }}</th>
                    <th class="p-3 text-left">{{ __('ui.end_date') }}</th>
                    <th class="p-3 text-left">{{ __('ui.amount') }}</th>
                    <th class="p-3 text-left">{{ __('ui.status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historial as $h)
                    <tr class="border-b border-white/10 hover:bg-white/5">
                        <td class="p-3">{{ __('ui.plan_labels.' . $h->plan) }}</td>
                        <td class="whitespace-nowrap p-3">{{ $h->fecha_inicio->format('d/m/Y') }}</td>
                        <td class="whitespace-nowrap p-3">{{ $h->fecha_fin->format('d/m/Y') }}</td>
                        <td class="whitespace-nowrap p-3">S/ {{ $h->monto }}</td>
                        <td class="p-3">
                            <span class="whitespace-nowrap rounded bg-white/10 px-2 py-1 text-xs text-white/80">
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
