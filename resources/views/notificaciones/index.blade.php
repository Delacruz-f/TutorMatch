@extends('layouts.app')

@section('title', __('ui.notifications'))

@php
    use App\Models\Notificacion;

    $tipos = Notificacion::TIPOS;

    $iconoTipo = [
        'clase' => '🎓',
        'pago' => '💳',
        'retiro' => '💰',
        'suscripcion' => '⭐',
        'resena' => '📝',
        'sistema' => '🔔',
    ];

@endphp

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- ENCABEZADO + ACCIONES --}}
    <div class="glass rounded-2xl p-6 text-white">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold flex items-center gap-2">
                    <x-icon name="bell" class="w-7 h-7" /> {{ __('ui.notifications') }}
                </h1>
                <p class="text-sm text-white/70 mt-1">
                    Historial completo de tus notificaciones.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if($noLeidas > 0)
                    <form method="POST" action="{{ route('notificaciones.leer-todas') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 bg-orange-600 text-white px-4 py-2 rounded-xl hover:bg-orange-700 text-sm font-semibold transition">
                            <x-icon name="check" class="w-4 h-4" /> Marcar todas como leídas
                        </button>
                    </form>
                @endif

                @if($leidas > 0)
                    <form method="POST" action="{{ route('notificaciones.vaciar') }}"
                          onsubmit="return confirm(@json(__('ui.delete_read_notifications_confirm')));">
                        @csrf
                        <input type="hidden" name="solo_leidas" value="1">
                        <button type="submit" class="inline-flex items-center gap-2 glass px-4 py-2 rounded-xl hover:bg-white/25 text-white text-sm font-semibold transition">
                            Eliminar leídas
                        </button>
                    </form>
                @endif

                @if($total > 0)
                    <form method="POST" action="{{ route('notificaciones.vaciar') }}"
                          onsubmit="return confirm(@json(__('ui.clear_notification_history_confirm')));">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 bg-red-600/80 hover:bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-semibold transition">
                            Vaciar historial
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- ESTADÍSTICAS --}}
        <div class="grid grid-cols-3 gap-3 mt-5">
            <div class="rounded-xl bg-white/15 border border-white/20 p-3 text-center">
                <p class="text-2xl font-bold text-blue-201">{{ $noLeidas }}</p>
                <p class="text-xs text-white/80 font-medium">{{ __('ui.unread') }}</p>
            </div>
            <div class="rounded-xl bg-white/10 border border-white/15 p-3 text-center">
                <p class="text-2xl font-bold">{{ $leidas }}</p>
                <p class="text-xs text-white/70 font-medium">{{ __('ui.read') }}</p>
            </div>
            <div class="rounded-xl bg-white/10 border border-white/15 p-3 text-center">
                <p class="text-2xl font-bold text-orange-300">{{ $total }}</p>
                <p class="text-xs text-white/70 font-medium">{{ __('ui.total') }}</p>
            </div>
        </div>
    </div>

    {{-- FILTROS --}}
    <div class="glass rounded-2xl p-5 space-y-4 text-white">
        <div class="flex flex-wrap gap-2">
            @php
                $chips = [
                    ['label' => 'Todas', 'params' => array_filter(['tipo' => $tipo, 'q' => $busqueda !== '' ? $busqueda : null]), 'active' => $estado === 'todas'],
                    ['label' => 'Sin leer', 'params' => array_filter(['estado' => 'no_leidas', 'tipo' => $tipo, 'q' => $busqueda !== '' ? $busqueda : null]), 'active' => $estado === 'no_leidas'],
                    ['label' => 'Leídas', 'params' => array_filter(['estado' => 'leidas', 'tipo' => $tipo, 'q' => $busqueda !== '' ? $busqueda : null]), 'active' => $estado === 'leidas'],
                ];
            @endphp

            @foreach($chips as $chip)
                <a href="{{ route('notificaciones.index', $chip['params']) }}"
                   class="px-3 py-1.5 rounded-full text-sm font-semibold transition {{ $chip['active'] ? 'bg-orange-600 text-white' : 'bg-white/15 text-white/90 hover:bg-white/25' }}">
                    {{ $chip['label'] }}
                </a>
            @endforeach
        </div>

        <div class="flex flex-wrap gap-2 items-center">
            <a href="{{ route('notificaciones.index', array_filter(['estado' => $estado !== 'todas' ? $estado : null, 'q' => $busqueda !== '' ? $busqueda : null])) }}"
               class="px-3 py-1.5 rounded-full text-sm font-semibold transition {{ $tipo === null ? 'bg-white text-gray-900' : 'bg-white/15 text-white/90 hover:bg-white/25' }}">
                Todos los tipos
            </a>

            @foreach($tipos as $clave => $label)
                @if(($porTipo[$clave] ?? 0) > 0 || $tipo === $clave)
                    <a href="{{ route('notificaciones.index', array_filter(['estado' => $estado !== 'todas' ? $estado : null, 'tipo' => $clave, 'q' => $busqueda !== '' ? $busqueda : null])) }}"
                       class="px-3 py-1.5 rounded-full text-sm font-semibold transition {{ $tipo === $clave ? 'bg-white text-gray-900' : 'bg-white/15 text-white/90 hover:bg-white/25' }}">
                        {{ $iconoTipo[$clave] ?? '🔔' }} {{ $label }}
                        <span class="text-xs opacity-70">({{ $porTipo[$clave] ?? 0 }})</span>
                    </a>
                @endif
            @endforeach
        </div>

        <form method="GET" action="{{ route('notificaciones.index') }}" class="flex gap-2">
            @if($estado !== 'todas')
                <input type="hidden" name="estado" value="{{ $estado }}">
            @endif
            @if($tipo)
                <input type="hidden" name="tipo" value="{{ $tipo }}">
            @endif

            <input type="text" name="q" value="{{ $busqueda }}" placeholder="{{ __('ui.search_history') }}"
                   class="input-glass flex-1 px-4 py-2 rounded-xl text-sm">
            <button type="submit" class="bg-white text-gray-900 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-white/90 transition">
                Buscar
            </button>
            @if($busqueda !== '')
                <a href="{{ route('notificaciones.index', array_filter(['estado' => $estado !== 'todas' ? $estado : null, 'tipo' => $tipo])) }}"
                   class="px-4 py-2 rounded-xl text-sm font-semibold text-white/80 hover:bg-white/15 transition">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    {{-- LISTA / HISTORIAL --}}
    @if($notificaciones->isEmpty())
        <div class="glass rounded-2xl p-12 text-center text-white">
            <div class="text-5xl mb-3">🔔</div>
            <p class="font-semibold">
                @if($busqueda !== '' || $tipo || $estado !== 'todas')
                    No hay notificaciones que coincidan con el filtro.
                @else
                    Aún no tienes notificaciones.
                @endif
            </p>
            <p class="text-sm text-white/70 mt-1">
                Aquí verás los avisos de tus clases, pagos, retiros y reseñas.
            </p>
        </div>
    @else
        <div class="space-y-2">
            @foreach($notificaciones as $n)
                <div class="rounded-2xl p-4 flex gap-4 transition border {{ $n->leida ? 'glass border-white/15' : 'bg-blue-600/30 border-blue-300/40 backdrop-blur-md' }}">

                    <div class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center text-lg bg-white/15 border border-white/20">
                        {{ $iconoTipo[$n->tipo] ?? '🔔' }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="font-bold text-white">
                                {{ $n->titulo }}
                            </h3>

                            <span class="text-[11px] uppercase tracking-wide font-bold px-2 py-0.5 rounded-full bg-white/20 text-white">
                                {{ $n->tipoLabel() }}
                            </span>

                            @if(! $n->leida)
                                <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-blue-600 text-white">
                                    Nueva
                                </span>
                            @endif
                        </div>

                        <p class="text-sm text-white/80 mt-1 break-words">{{ $n->mensaje }}</p>

                        <p class="text-xs text-white/60 mt-2">
                            {{ $n->created_at->diffForHumans() }}
                            <span class="text-white/30">·</span>
                            {{ $n->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <div class="shrink-0 flex flex-col sm:flex-row items-end sm:items-center gap-1">
                        <form method="POST" action="{{ route('notificaciones.leer', $n->id) }}">
                            @csrf
                            <button type="submit"
                                    class="px-3 py-1.5 rounded-lg text-sm font-semibold transition {{ $n->leida ? 'text-white/80 hover:bg-white/15' : 'bg-white text-gray-900 hover:bg-white/90' }}">
                                {{ $n->urlSegura() ? 'Ver' : ($n->leida ? 'Leída' : 'Marcar leída') }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('notificaciones.destroy', $n->id) }}"
                              onsubmit="return confirm(@json(__('ui.delete_notification_confirm')));">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-3 py-1.5 rounded-lg text-sm font-semibold text-red-300 hover:bg-red-500/20 transition"
                                    title="{{ __('ui.delete_notification') }}">
                                {{ __('ui.delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $notificaciones->links() }}
        </div>
    @endif
</div>
@endsection
