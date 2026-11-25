@extends('layouts.app')

@section('title', __('ui.my_reviews_title'))

@section('content')
<div class="glass rounded-2xl p-6 text-white mb-6">
    <h1 class="text-3xl font-bold mb-1 flex items-center"><x-icon name="star" class="w-7 h-7" /> {{ __('ui.my_reviews_title') }}</h1>
    <p class="text-white/80">{{ __('ui.student_feedback') }}</p>
</div>

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="glass rounded-2xl p-6 text-white text-center">
        <p class="text-5xl font-bold">{{ number_format($promedio, 1) }}</p>
        <div class="flex items-center justify-center gap-1 mt-1">
            @for($i = 1; $i <= 5; $i++)
                <x-icon name="star" class="w-6 h-6 {{ $i <= round($promedio) ? 'icon-filled' : 'opacity-30' }}" />
            @endfor
        </div>
        <p class="text-sm text-white/70 mt-2">{{ $totalResenas }} reseñas</p>
    </div>

    <div class="glass rounded-2xl p-6 text-white md:col-span-2">
        <p class="text-sm font-bold mb-3">{{ __('ui.rating_distribution') }}</p>
        @for($i = 5; $i >= 1; $i--)
            @php
                $porcentaje = $totalResenas > 0 ? ($distribucion[$i] / $totalResenas) * 100 : 0;
            @endphp
            <div class="flex items-center gap-2 mb-1 text-sm">
                <span class="w-6 flex items-center gap-1">{{ $i }} <x-icon name="star" class="w-3 h-3 icon-filled" /></span>
                <div class="flex-1 bg-white/20 rounded-full h-2">
                    <div class="bg-orange-400 h-2 rounded-full" style="width: {{ $porcentaje }}%"></div>
                </div>
                <span class="w-8 text-right">{{ $distribucion[$i] }}</span>
            </div>
        @endfor
    </div>
</div>

<!-- Lista de reseñas -->
<div class="glass rounded-2xl p-6 text-white">
    @if($resenas->isEmpty())
        <p class="text-center py-8 text-white/70">{{ __('ui.no_reviews_keep_teaching') }}</p>
    @else
        <div class="space-y-4">
            @foreach($resenas as $r)
                <div class="bg-white/10 rounded-xl p-4 border border-white/20">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center gap-3">
                            @if($r->estudiante->foto)
                                <img src="{{ Storage::url($r->estudiante->foto) }}" class="w-10 h-10 rounded-full object-cover">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-400 to-purple-500 flex items-center justify-center font-bold">
                                    {{ substr($r->estudiante->nombre, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <p class="font-bold">{{ $r->estudiante->nombre }} {{ $r->estudiante->apellido }}</p>
                                <p class="text-xs text-white/60">
                                    {{ $r->clase->materia->nombre ?? 'Clase' }} · {{ $r->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="flex items-center gap-0.5 justify-end">
                                @for($s = 0; $s < $r->calificacion; $s++)<x-icon name="star" class="w-4 h-4 icon-filled" />@endfor
                            </p>
                            @if($r->estado === 'oculta')
                                <span class="text-xs bg-red-400/30 px-2 py-0.5 rounded">{{ __('ui.hidden') }}</span>
                            @endif
                        </div>
                    </div>

                    @if($r->comentario)
                        <p class="text-white/90 mt-2">{{ $r->comentario }}</p>
                    @endif

                    <!-- Respuesta del tutor -->
                    @if($r->respuesta_tutor)
                        <div class="mt-3 ml-6 p-3 bg-blue-400/20 rounded-lg border-l-4 border-blue-400">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-bold text-blue-200">{{ __('ui.tutor_response') }}</p>
                                    <p class="text-sm mt-1">{{ $r->respuesta_tutor }}</p>
                                </div>
                                <form method="POST" action="{{ route('tutor.resenas.eliminar-respuesta', $r->id) }}" onsubmit="return confirm(@json(__('ui.delete_reply_confirm')))">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-300 hover:text-red-100 text-xs" title="{{ __('ui.delete_reply') }}"><x-icon name="trash" class="w-4 h-4" /></button>
                                </form>
                            </div>
                        </div>
                    @else
                        <details class="mt-3">
                            <summary class="cursor-pointer text-sm text-blue-200 hover:text-blue-100 font-medium inline-flex items-center gap-1.5 [&::-webkit-details-marker]:hidden">
                                <x-icon name="message-circle" class="w-4 h-4" /> Responder
                            </summary>
                            <form method="POST" action="{{ route('tutor.resenas.responder', $r->id) }}" class="mt-2 space-y-2">
                                @csrf
                                <textarea name="respuesta_tutor" rows="2" maxlength="500" required
                                          placeholder="{{ __('ui.reply_thanks_placeholder') }}"
                                          class="w-full px-3 py-2 rounded-xl bg-white/15 border border-white/30 text-white placeholder-white/50 focus:bg-white/25 focus:outline-none text-sm"></textarea>
                                <button type="submit" class="btn-white inline-flex items-center gap-1.5 px-4 py-2 font-bold text-sm">
                                    <x-icon name="check" class="w-4 h-4" /> Enviar respuesta
                                </button>
                            </form>
                        </details>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $resenas->links() }}</div>
    @endif
</div>
@endsection