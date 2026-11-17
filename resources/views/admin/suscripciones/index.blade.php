@extends('layouts.app')

@section('title', __('ui.manage_subscriptions'))

@section('content')
<div class="glass rounded-2xl p-6 text-white mb-6">
    <h1 class="text-3xl font-bold mb-1 flex items-center"><x-icon name="sparkles" class="w-7 h-7" /> {{ __('ui.subscriptions') }}</h1>
    <p class="text-white/80">{{ __('ui.plans_for_tutors') }}</p>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="glass rounded-2xl p-5 text-white">
        <p class="text-xs text-white/70 uppercase">{{ __('ui.active_subscriptions') }}</p>
        <p class="text-3xl font-bold">{{ $activas }}</p>
    </div>
    <div class="glass rounded-2xl p-5 text-white">
        <p class="text-xs text-white/70 uppercase">{{ __('ui.monthly_income') }}</p>
        <p class="text-3xl font-bold">S/ {{ number_format($ingresosMes, 2) }}</p>
    </div>
    <div class="glass rounded-2xl p-5 text-white">
        <p class="text-xs text-white/70 uppercase">{{ __('ui.featured_tutors') }}</p>
        <p class="text-3xl font-bold">{{ $tutoresDestacados->count() }}</p>
    </div>
</div>

<!-- Tutores activos con plan -->
<div class="glass rounded-2xl p-6 text-white mb-6">
    <h2 class="text-xl font-bold mb-4 flex items-center"><x-icon name="trophy" class="w-5 h-5" /> {{ __('ui.active_featured_tutors') }}</h2>
    @if($tutoresDestacados->isEmpty())
        <p class="text-white/60 text-sm">{{ __('ui.no_active_plan_tutors') }}</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($tutoresDestacados as $t)
                <div class="bg-white/10 rounded-xl p-4 border border-white/20">
                    <div class="flex items-center gap-3">
                        @if($t->user->foto)
                            <img src="{{ Storage::url($t->user->foto) }}" class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-400 to-purple-500 flex items-center justify-center font-bold">
                                {{ substr($t->user->nombre, 0, 1) }}
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="font-bold text-sm">{{ $t->user->nombre }} {{ $t->user->apellido }}</p>
                            <p class="text-xs text-white/70">
                                <span class="{{ $t->plan === 'premium' ? 'text-yellow-300' : 'text-blue-300' }}">
                                    <x-icon :name="$t->plan === 'premium' ? 'crown' : 'star'" class="w-3.5 h-3.5" /> {{ $t->plan === 'premium' ? 'Premium' : 'Destacado' }}
                                </span>
                                · Vence {{ $t->plan_vence->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Filtros -->
<div class="glass rounded-2xl p-4 text-white mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <select name="estado" class="px-4 py-2 rounded-xl bg-white/15 border border-white/30 text-white">
            <option value="" class="text-gray-800">{{ __('ui.all_states') }}</option>
            <option value="activa" {{ request('estado') == 'activa' ? 'selected' : '' }} class="text-gray-800">{{ __('ui.active_plan') }}</option>
            <option value="vencida" {{ request('estado') == 'vencida' ? 'selected' : '' }} class="text-gray-800">{{ __('ui.expired_plan') }}</option>
            <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }} class="text-gray-800">{{ __('ui.cancelled') }}</option>
        </select>
        <select name="plan" class="px-4 py-2 rounded-xl bg-white/15 border border-white/30 text-white">
            <option value="" class="text-gray-800">{{ __('ui.all_plans') }}</option>
            <option value="destacado" {{ request('plan') == 'destacado' ? 'selected' : '' }} class="text-gray-800">{{ __('ui.plan_labels.destacado') }}</option>
            <option value="premium" {{ request('plan') == 'premium' ? 'selected' : '' }} class="text-gray-800">{{ __('ui.plan_labels.premium') }}</option>
        </select>
        <div></div>
        <button type="submit" class="bg-white text-brand-600 py-2 rounded-xl font-bold hover:bg-white/90">{{ __('ui.filter') }}</button>
    </form>
</div>

<!-- Tabla -->
<div class="glass rounded-2xl p-6 text-white">
    @if($suscripciones->isEmpty())
        <p class="text-center py-8 text-white/70">{{ __('ui.no_subscriptions_registered') }}</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-white/20">
                        <th class="text-left p-3 font-semibold text-white/80">{{ __('ui.tutor') }}</th>
                        <th class="text-left p-3 font-semibold text-white/80">{{ __('ui.plan') }}</th>
                        <th class="text-left p-3 font-semibold text-white/80">{{ __('ui.amount') }}</th>
                        <th class="text-left p-3 font-semibold text-white/80">{{ __('ui.home') }}</th>
                        <th class="text-left p-3 font-semibold text-white/80">{{ __('ui.end_date') }}</th>
                        <th class="text-left p-3 font-semibold text-white/80">{{ __('ui.status') }}</th>
                        <th class="text-left p-3 font-semibold text-white/80">{{ __('ui.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suscripciones as $s)
                        <tr class="border-b border-white/10 hover:bg-white/5">
                            <td class="p-3">{{ $s->tutor->user->nombre }} {{ $s->tutor->user->apellido }}</td>
                            <td class="p-3 capitalize">{{ __('ui.plan_labels.' . $s->plan) }}</td>
                            <td class="p-3">S/ {{ $s->monto }}</td>
                            <td class="p-3 text-xs">{{ $s->fecha_inicio->format('d/m/Y') }}</td>
                            <td class="p-3 text-xs">{{ $s->fecha_fin->format('d/m/Y') }}</td>
                            <td class="p-3">
                                <span class="text-xs px-2 py-1 rounded
                                    {{ $s->estado === 'activa' ? 'bg-green-400/30' : '' }}
                                    {{ $s->estado === 'vencida' ? 'bg-yellow-400/30' : '' }}
                                    {{ $s->estado === 'cancelada' ? 'bg-red-400/30' : '' }}">
                                    {{ __('ui.status_labels.' . $s->estado) }}
                                </span>
                            </td>
                            <td class="p-3">
                                <div class="flex gap-1">
                                    @if($s->estado === 'activa')
                                        <form method="POST" action="{{ route('admin.suscripciones.extender', $s->id) }}" class="inline flex gap-1">
                                            @csrf
                                            <input type="number" name="meses" value="1" min="1" max="12" class="w-14 px-2 py-1 rounded bg-white/15 border border-white/30 text-white text-xs">
                                            <button class="inline-flex items-center justify-center bg-white/10 hover:bg-white/20 border border-white/15 p-1.5 rounded-lg" title="{{ __('ui.extend') }}"><x-icon name="plus" class="w-4 h-4" /></button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.suscripciones.cancelar', $s->id) }}" onsubmit="return confirm(@json(__('ui.cancel_subscription_confirm')))">
                                            @csrf
                                            <button class="inline-flex items-center justify-center bg-white/10 hover:bg-white/20 border border-white/15 p-1.5 rounded-lg" title="{{ __('ui.cancel') }}"><x-icon name="x" class="w-4 h-4" /></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $suscripciones->links() }}</div>
    @endif
</div>
@endsection