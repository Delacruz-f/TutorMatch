@extends('layouts.app')

@section('title', 'Transacciones')

@section('content')
<div class="glass rounded-2xl p-6 text-white mb-6">
    <h1 class="text-3xl font-bold mb-1">💸 Transacciones</h1>
    <p class="text-white/80">Historial completo de movimientos</p>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="glass rounded-2xl p-5 text-white">
        <p class="text-xs text-white/70 uppercase">Total Ingresos</p>
        <p class="text-2xl font-bold">S/ {{ number_format($totalIngresos, 2) }}</p>
    </div>
    <div class="glass rounded-2xl p-5 text-white">
        <p class="text-xs text-white/70 uppercase">Comisiones</p>
        <p class="text-2xl font-bold text-green-200">S/ {{ number_format($totalComisiones, 2) }}</p>
    </div>
    <div class="glass rounded-2xl p-5 text-white">
        <p class="text-xs text-white/70 uppercase">Retiros Pagados</p>
        <p class="text-2xl font-bold text-red-200">S/ {{ number_format($totalRetiros, 2) }}</p>
    </div>
    <div class="glass rounded-2xl p-5 text-white">
        <p class="text-xs text-white/70 uppercase">Pagos Pendientes</p>
        <p class="text-2xl font-bold text-yellow-200">{{ $pagosPendientes }}</p>
    </div>
</div>

<!-- Filtros -->
<div class="glass rounded-2xl p-4 text-white mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <select name="concepto" class="px-4 py-2 rounded-xl bg-white/15 border border-white/30 text-white">
            <option value="" class="text-gray-800">Todos los conceptos</option>
            <option value="clase" {{ request('concepto') == 'clase' ? 'selected' : '' }} class="text-gray-800">Clases</option>
            <option value="suscripcion" {{ request('concepto') == 'suscripcion' ? 'selected' : '' }} class="text-gray-800">Suscripciones</option>
        </select>
        <select name="metodo" class="px-4 py-2 rounded-xl bg-white/15 border border-white/30 text-white">
            <option value="" class="text-gray-800">Todos los métodos</option>
            <option value="yape" {{ request('metodo') == 'yape' ? 'selected' : '' }} class="text-gray-800">Yape</option>
            <option value="plin" {{ request('metodo') == 'plin' ? 'selected' : '' }} class="text-gray-800">Plin</option>
            <option value="tarjeta" {{ request('metodo') == 'tarjeta' ? 'selected' : '' }} class="text-gray-800">Tarjeta</option>
        </select>
        <select name="estado_pago" class="px-4 py-2 rounded-xl bg-white/15 border border-white/30 text-white">
            <option value="" class="text-gray-800">Todos los estados</option>
            <option value="pendiente" {{ request('estado_pago') == 'pendiente' ? 'selected' : '' }} class="text-gray-800">Pendiente</option>
            <option value="pagado" {{ request('estado_pago') == 'pagado' ? 'selected' : '' }} class="text-gray-800">Pagado</option>
            <option value="fallido" {{ request('estado_pago') == 'fallido' ? 'selected' : '' }} class="text-gray-800">Fallido</option>
        </select>
        <button type="submit" class="bg-white text-brand-600 py-2 rounded-xl font-bold">Filtrar</button>
    </form>

    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3 mt-3">
        <input type="date" name="desde" value="{{ request('desde') }}" class="px-4 py-2 rounded-xl bg-white/15 border border-white/30 text-white">
        <input type="date" name="hasta" value="{{ request('hasta') }}" class="px-4 py-2 rounded-xl bg-white/15 border border-white/30 text-white">
        <input type="number" name="monto_min" value="{{ request('monto_min') }}" placeholder="Monto mínimo" class="px-4 py-2 rounded-xl bg-white/15 border border-white/30 text-white placeholder-white/60">
        <button type="submit" class="bg-white/20 hover:bg-white/30 py-2 rounded-xl font-bold">Aplicar rango</button>
    </form>
</div>

<!-- Pagos -->
<div class="glass rounded-2xl p-6 text-white mb-6">
    <h2 class="text-xl font-bold mb-4">💳 Últimos Pagos</h2>
    @if($pagos->isEmpty())
        <p class="text-center py-6 text-white/70">No hay pagos con esos filtros.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-white/20">
                        <th class="text-left p-2 font-semibold text-white/80">Fecha</th>
                        <th class="text-left p-2 font-semibold text-white/80">Usuario</th>
                        <th class="text-left p-2 font-semibold text-white/80">Concepto</th>
                        <th class="text-left p-2 font-semibold text-white/80">Método</th>
                        <th class="text-right p-2 font-semibold text-white/80">Monto</th>
                        <th class="text-left p-2 font-semibold text-white/80">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagos as $p)
                        <tr class="border-b border-white/10 hover:bg-white/5">
                            <td class="p-2 text-xs">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-2">{{ $p->user->nombre }} {{ $p->user->apellido }}</td>
                            <td class="p-2 capitalize text-xs">{{ $p->concepto }}</td>
                            <td class="p-2 uppercase text-xs">{{ $p->metodo }}</td>
                            <td class="p-2 text-right font-bold">S/ {{ number_format($p->monto, 2) }}</td>
                            <td class="p-2">
                                <span class="text-xs px-2 py-0.5 rounded
                                    {{ $p->estado === 'pagado' ? 'bg-green-400/30' : '' }}
                                    {{ $p->estado === 'pendiente' ? 'bg-yellow-400/30' : '' }}
                                    {{ $p->estado === 'fallido' ? 'bg-red-400/30' : '' }}">
                                    {{ ucfirst($p->estado) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Retiros -->
<div class="glass rounded-2xl p-6 text-white">
    <h2 class="text-xl font-bold mb-4">💰 Últimos Retiros</h2>
    @if($retiros->isEmpty())
        <p class="text-center py-6 text-white/70">No hay retiros.</p>
    @else
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-white/20">
                    <th class="text-left p-2 font-semibold text-white/80">Fecha</th>
                    <th class="text-left p-2 font-semibold text-white/80">Tutor</th>
                    <th class="text-left p-2 font-semibold text-white/80">Método</th>
                    <th class="text-right p-2 font-semibold text-white/80">Monto</th>
                    <th class="text-left p-2 font-semibold text-white/80">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($retiros as $r)
                    <tr class="border-b border-white/10">
                        <td class="p-2 text-xs">{{ $r->created_at->format('d/m/Y') }}</td>
                        <td class="p-2">{{ $r->tutor->user->nombre }}</td>
                        <td class="p-2 uppercase text-xs">{{ $r->metodo }}</td>
                        <td class="p-2 text-right font-bold">S/ {{ number_format($r->monto, 2) }}</td>
                        <td class="p-2">
                            <span class="text-xs px-2 py-0.5 rounded
                                {{ $r->estado === 'pagado' ? 'bg-green-400/30' : '' }}
                                {{ $r->estado === 'pendiente' ? 'bg-yellow-400/30' : '' }}
                                {{ $r->estado === 'rechazado' ? 'bg-red-400/30' : '' }}">
                                {{ ucfirst($r->estado) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection