@extends('layouts.app')

@section('title', __('ui.admin_dashboard'))

@section('content')
<!-- Bienvenida -->
<div class="glass rounded-2xl p-6 text-white mb-6">
    <h1 class="text-3xl font-bold mb-1">{{ __('ui.admin_dashboard') }}</h1>
    <p class="text-white/80 flex items-center gap-2">{{ __('ui.welcome_user', ['name' => Auth::user()->nombre]) }} <x-icon name="settings" class="w-4 h-4" /></p>
</div>

<!-- Tarjetas de estadísticas -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="glass rounded-2xl p-5 text-white">
        <div class="flex justify-between items-center mb-2">
            <span class="icon-chip"><x-icon name="users" /></span>
            <span class="text-xs text-white/60">{{ __('ui.total') }}</span>
        </div>
        <p class="text-3xl font-bold">{{ $totalUsuarios }}</p>
        <p class="text-sm text-white/70">{{ __('ui.users') }}</p>
    </div>

    <div class="glass rounded-2xl p-5 text-white">
        <div class="flex justify-between items-center mb-2">
            <span class="icon-chip"><x-icon name="graduation-cap" /></span>
            <span class="text-xs text-white/60">{{ __('ui.students') }}</span>
        </div>
        <p class="text-3xl font-bold">{{ $totalEstudiantes }}</p>
        <p class="text-sm text-white/70">{{ __('ui.registered') }}</p>
    </div>

    <div class="glass rounded-2xl p-5 text-white">
        <div class="flex justify-between items-center mb-2">
            <span class="icon-chip"><x-icon name="user-check" /></span>
            <span class="text-xs text-white/60">{{ __('ui.tutors') }}</span>
        </div>
        <p class="text-3xl font-bold">{{ $totalTutores }}</p>
        <p class="text-sm text-white/70">
            {{ __('ui.approved') }}: {{ $tutoresAprobados }},
            <span class="text-yellow-200">{{ __('ui.pending') }}: {{ $tutoresPendientes }}</span>
        </p>
    </div>

    <div class="glass rounded-2xl p-5 text-white">
        <div class="flex justify-between items-center mb-2">
            <span class="icon-chip"><x-icon name="wallet" /></span>
            <span class="text-xs text-white/60">{{ __('ui.this_month') }}</span>
        </div>
        <p class="text-3xl font-bold">S/ {{ number_format($ingresosTotalesMes, 2) }}</p>
        <p class="text-sm text-white/70">{{ __('ui.platform_revenue') }}</p>
    </div>
</div>

<!-- Alertas -->
@if($pagosPendientes > 0 || $retirosPendientes > 0 || $tutoresPendientes > 0)
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    @if($tutoresPendientes > 0)
        <a href="{{ route('admin.tutores.index') }}" class="glass rounded-2xl p-4 text-white hover:bg-white/25 transition border-l-4 border-yellow-400">
            <div class="flex items-center gap-3">
                <span class="icon-chip"><x-icon name="clock" /></span>
                <div>
                    <p class="font-bold">{{ __('ui.pending_tutors_count', ['count' => $tutoresPendientes]) }}</p>
                    <p class="text-xs text-white/70">{{ __('ui.requires_approval') }}</p>
                </div>
            </div>
        </a>
    @endif

    @if($pagosPendientes > 0)
        <a href="{{ route('admin.pagos.index') }}" class="glass rounded-2xl p-4 text-white hover:bg-white/25 transition border-l-4 border-green-400">
            <div class="flex items-center gap-3">
                <span class="icon-chip"><x-icon name="credit-card" /></span>
                <div>
                    <p class="font-bold">{{ __('ui.pending_payments_count', ['count' => $pagosPendientes]) }}</p>
                    <p class="text-xs text-white/70">{{ __('ui.requires_validation') }}</p>
                </div>
            </div>
        </a>
    @endif

    @if($retirosPendientes > 0)
        <a href="{{ route('admin.retiros.index') }}" class="glass rounded-2xl p-4 text-white hover:bg-white/25 transition border-l-4 border-red-400">
            <div class="flex items-center gap-3">
                <span class="icon-chip"><x-icon name="wallet" /></span>
                <div>
                    <p class="font-bold">{{ __('ui.pending_withdrawals_count', ['count' => $retirosPendientes]) }}</p>
                    <p class="text-xs text-white/70">{{ __('ui.requires_processing') }}</p>
                </div>
            </div>
        </a>
    @endif
</div>
@endif

<!-- Accesos rápidos -->
<div class="glass rounded-2xl p-6 text-white mb-6">
    <h2 class="text-xl font-bold mb-4 flex items-center gap-2"><x-icon name="sparkles" class="w-5 h-5" /> {{ __('ui.quick_access') }}</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
        <a href="{{ route('admin.usuarios.index') }}" class="bg-white/15 hover:bg-white/25 p-4 rounded-xl transition text-center">
            <div class="icon-chip mb-2"><x-icon name="users" /></div>
            <p class="font-medium text-sm">{{ __('ui.users') }}</p>
        </a>
        <a href="{{ route('admin.tutores.index') }}" class="bg-white/15 hover:bg-white/25 p-4 rounded-xl transition text-center">
            <div class="icon-chip mb-2"><x-icon name="graduation-cap" /></div>
            <p class="font-medium text-sm">{{ __('ui.tutors') }}</p>
        </a>
        <a href="{{ route('admin.materias.index') }}" class="bg-white/15 hover:bg-white/25 p-4 rounded-xl transition text-center">
            <div class="icon-chip mb-2"><x-icon name="book-open" /></div>
            <p class="font-medium text-sm">{{ __('ui.subjects') }}</p>
        </a>
        <a href="{{ route('admin.pagos.index') }}" class="bg-white/15 hover:bg-white/25 p-4 rounded-xl transition text-center">
            <div class="icon-chip mb-2"><x-icon name="credit-card" /></div>
            <p class="font-medium text-sm">{{ __('ui.payments') }}</p>
        </a>
        <a href="{{ route('admin.retiros.index') }}" class="bg-white/15 hover:bg-white/25 p-4 rounded-xl transition text-center">
            <div class="icon-chip mb-2"><x-icon name="wallet" /></div>
            <p class="font-medium text-sm">{{ __('ui.withdrawals') }}</p>
        </a>
        <a href="{{ route('admin.resenas.index') }}" class="bg-white/15 hover:bg-white/25 p-4 rounded-xl transition text-center">
            <div class="icon-chip mb-2"><x-icon name="star" /></div>
            <p class="font-medium text-sm">{{ __('ui.reviews') }}</p>
        </a>
        <a href="{{ route('admin.reportes.index') }}" class="bg-white/15 hover:bg-white/25 p-4 rounded-xl transition text-center">
            <div class="icon-chip mb-2"><x-icon name="chart" /></div>
            <p class="font-medium text-sm">{{ __('ui.reports') }}</p>
        </a>
        <a href="{{ route('notificaciones.index') }}" class="bg-white/15 hover:bg-white/25 p-4 rounded-xl transition text-center">
            <div class="icon-chip mb-2"><x-icon name="bell" /></div>
            <p class="font-medium text-sm">{{ __('ui.notifications') }}</p>
        </a>
    </div>
</div>

<!-- Últimas clases y pagos -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Últimas clases -->
    <div class="glass rounded-2xl p-6 text-white">
        <h2 class="text-xl font-bold mb-4 flex items-center gap-2"><x-icon name="book-open" class="w-5 h-5" /> {{ __('ui.recent_booked_classes') }}</h2>
        @if($ultimasClases->isEmpty())
            <p class="text-white/60 text-sm">{{ __('ui.no_recent_classes') }}</p>
        @else
            <div class="space-y-2">
                @foreach($ultimasClases as $c)
                    <div class="bg-white/10 rounded-lg p-3 text-sm">
                        <p class="font-medium">{{ $c->estudiante->nombre }} → {{ $c->tutor->user->nombre }}</p>
                        <p class="text-xs text-white/70">{{ $c->materia->nombre }} · {{ $c->fecha->format('d/m/Y') }}</p>
                        <p class="text-xs text-white/60 mt-1">{{ __('ui.class_status') }} <span>{{ __('ui.status_labels.' . $c->estado) }}</span></p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Últimos pagos pendientes -->
    <div class="glass rounded-2xl p-6 text-white">
        <h2 class="text-xl font-bold mb-4 flex items-center gap-2"><x-icon name="credit-card" class="w-5 h-5" /> {{ __('ui.pending_payments') }}</h2>
        @if($ultimosPagos->isEmpty())
            <p class="text-white/60 text-sm flex items-center gap-2">{{ __('ui.no_pending_payments') }} <x-icon name="check-circle" class="w-4 h-4" /></p>
        @else
            <div class="space-y-2">
                @foreach($ultimosPagos as $p)
                    <div class="bg-white/10 rounded-lg p-3 text-sm">
                        <p class="font-medium">{{ $p->user->nombre }} {{ $p->user->apellido }}</p>
                        <p class="text-xs text-white/70">S/ {{ $p->monto }} · {{ __('ui.method_labels.' . $p->metodo) }} · {{ __('ui.concept_labels.' . $p->concepto) }}</p>
                    </div>
                @endforeach
            </div>
            <a href="{{ route('admin.pagos.index') }}" class="inline-flex items-center justify-center gap-1.5 w-full mt-3 text-sm text-white/80 hover:text-white underline">
                {{ __('ui.see_all') }} <x-icon name="arrow-right" class="w-4 h-4" />
            </a>
        @endif
    </div>
</div>
@endsection