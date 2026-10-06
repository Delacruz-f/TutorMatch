@extends('layouts.app')

@section('title', 'Panel Administrador')

@section('content')
<!-- Bienvenida -->
<div class="glass rounded-2xl p-6 text-white mb-6">
    <h1 class="text-3xl font-bold mb-1">Panel de Administrador</h1>
    <p class="text-white/80">Bienvenido, {{ Auth::user()->nombre }} 🛠️</p>
</div>

<!-- Tarjetas de estadísticas -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="glass rounded-2xl p-5 text-white">
        <div class="flex justify-between items-center mb-2">
            <span class="text-3xl">👥</span>
            <span class="text-xs text-white/60">Total</span>
        </div>
        <p class="text-3xl font-bold">{{ $totalUsuarios }}</p>
        <p class="text-sm text-white/70">Usuarios</p>
    </div>

    <div class="glass rounded-2xl p-5 text-white">
        <div class="flex justify-between items-center mb-2">
            <span class="text-3xl">🎓</span>
            <span class="text-xs text-white/60">Estudiantes</span>
        </div>
        <p class="text-3xl font-bold">{{ $totalEstudiantes }}</p>
        <p class="text-sm text-white/70">Registrados</p>
    </div>

    <div class="glass rounded-2xl p-5 text-white">
        <div class="flex justify-between items-center mb-2">
            <span class="text-3xl">👨‍🏫</span>
            <span class="text-xs text-white/60">Tutores</span>
        </div>
        <p class="text-3xl font-bold">{{ $totalTutores }}</p>
        <p class="text-sm text-white/70">
            {{ $tutoresAprobados }} aprobados, 
            <span class="text-yellow-200">{{ $tutoresPendientes }} pendientes</span>
        </p>
    </div>

    <div class="glass rounded-2xl p-5 text-white">
        <div class="flex justify-between items-center mb-2">
            <span class="text-3xl">💰</span>
            <span class="text-xs text-white/60">Este mes</span>
        </div>
        <p class="text-3xl font-bold">S/ {{ number_format($ingresosTotalesMes, 2) }}</p>
        <p class="text-sm text-white/70">Ingresos plataforma</p>
    </div>
</div>

<!-- Alertas -->
@if($pagosPendientes > 0 || $retirosPendientes > 0 || $tutoresPendientes > 0)
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    @if($tutoresPendientes > 0)
        <a href="{{ route('admin.tutores.index') }}" class="glass rounded-2xl p-4 text-white hover:bg-white/25 transition border-l-4 border-yellow-400">
            <div class="flex items-center gap-3">
                <span class="text-3xl">⏳</span>
                <div>
                    <p class="font-bold">{{ $tutoresPendientes }} tutores pendientes</p>
                    <p class="text-xs text-white/70">Requieren aprobación</p>
                </div>
            </div>
        </a>
    @endif

    @if($pagosPendientes > 0)
        <a href="{{ route('admin.pagos.index') }}" class="glass rounded-2xl p-4 text-white hover:bg-white/25 transition border-l-4 border-green-400">
            <div class="flex items-center gap-3">
                <span class="text-3xl">💳</span>
                <div>
                    <p class="font-bold">{{ $pagosPendientes }} pagos pendientes</p>
                    <p class="text-xs text-white/70">Requieren validación</p>
                </div>
            </div>
        </a>
    @endif

    @if($retirosPendientes > 0)
        <a href="{{ route('admin.retiros.index') }}" class="glass rounded-2xl p-4 text-white hover:bg-white/25 transition border-l-4 border-red-400">
            <div class="flex items-center gap-3">
                <span class="text-3xl">💰</span>
                <div>
                    <p class="font-bold">{{ $retirosPendientes }} retiros pendientes</p>
                    <p class="text-xs text-white/70">Requieren procesamiento</p>
                </div>
            </div>
        </a>
    @endif
</div>
@endif

<!-- Accesos rápidos -->
<div class="glass rounded-2xl p-6 text-white mb-6">
    <h2 class="text-xl font-bold mb-4">🎯 Accesos Rápidos</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
        <a href="{{ route('admin.usuarios.index') }}" class="bg-white/15 hover:bg-white/25 p-4 rounded-xl transition text-center">
            <div class="text-3xl mb-2">👥</div>
            <p class="font-medium text-sm">Usuarios</p>
        </a>
        <a href="{{ route('admin.tutores.index') }}" class="bg-white/15 hover:bg-white/25 p-4 rounded-xl transition text-center">
            <div class="text-3xl mb-2">👨‍🏫</div>
            <p class="font-medium text-sm">Tutores</p>
        </a>
        <a href="{{ route('admin.materias.index') }}" class="bg-white/15 hover:bg-white/25 p-4 rounded-xl transition text-center">
            <div class="text-3xl mb-2">📚</div>
            <p class="font-medium text-sm">Materias</p>
        </a>
        <a href="{{ route('admin.pagos.index') }}" class="bg-white/15 hover:bg-white/25 p-4 rounded-xl transition text-center">
            <div class="text-3xl mb-2">💳</div>
            <p class="font-medium text-sm">Pagos</p>
        </a>
        <a href="{{ route('admin.retiros.index') }}" class="bg-white/15 hover:bg-white/25 p-4 rounded-xl transition text-center">
            <div class="text-3xl mb-2">💰</div>
            <p class="font-medium text-sm">Retiros</p>
        </a>
        <a href="{{ route('admin.resenas.index') }}" class="bg-white/15 hover:bg-white/25 p-4 rounded-xl transition text-center">
            <div class="text-3xl mb-2">⭐</div>
            <p class="font-medium text-sm">Reseñas</p>
        </a>
        <a href="{{ route('admin.reportes.index') }}" class="bg-white/15 hover:bg-white/25 p-4 rounded-xl transition text-center">
            <div class="text-3xl mb-2">📊</div>
            <p class="font-medium text-sm">Reportes</p>
        </a>
        <a href="{{ route('notificaciones.index') }}" class="bg-white/15 hover:bg-white/25 p-4 rounded-xl transition text-center">
            <div class="text-3xl mb-2">🔔</div>
            <p class="font-medium text-sm">Notificaciones</p>
        </a>
    </div>
</div>

<!-- Últimas clases y pagos -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Últimas clases -->
    <div class="glass rounded-2xl p-6 text-white">
        <h2 class="text-xl font-bold mb-4">📚 Últimas Clases Reservadas</h2>
        @if($ultimasClases->isEmpty())
            <p class="text-white/60 text-sm">Sin clases recientes.</p>
        @else
            <div class="space-y-2">
                @foreach($ultimasClases as $c)
                    <div class="bg-white/10 rounded-lg p-3 text-sm">
                        <p class="font-medium">{{ $c->estudiante->nombre }} → {{ $c->tutor->user->nombre }}</p>
                        <p class="text-xs text-white/70">{{ $c->materia->nombre }} · {{ $c->fecha->format('d/m/Y') }}</p>
                        <p class="text-xs text-white/60 mt-1">Estado: <span class="capitalize">{{ str_replace('_', ' ', $c->estado) }}</span></p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Últimos pagos pendientes -->
    <div class="glass rounded-2xl p-6 text-white">
        <h2 class="text-xl font-bold mb-4">💳 Pagos Pendientes</h2>
        @if($ultimosPagos->isEmpty())
            <p class="text-white/60 text-sm">Sin pagos pendientes. 🎉</p>
        @else
            <div class="space-y-2">
                @foreach($ultimosPagos as $p)
                    <div class="bg-white/10 rounded-lg p-3 text-sm">
                        <p class="font-medium">{{ $p->user->nombre }} {{ $p->user->apellido }}</p>
                        <p class="text-xs text-white/70">S/ {{ $p->monto }} · {{ strtoupper($p->metodo) }} · {{ ucfirst($p->concepto) }}</p>
                    </div>
                @endforeach
            </div>
            <a href="{{ route('admin.pagos.index') }}" class="block text-center mt-3 text-sm text-white/80 hover:text-white underline">
                Ver todos →
            </a>
        @endif
    </div>
</div>
@endsection