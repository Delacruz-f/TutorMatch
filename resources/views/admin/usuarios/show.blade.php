@extends('layouts.app')

@section('title', __('ui.user_details'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="glass rounded-2xl p-6 text-white">
        <div class="flex items-center gap-4">
            @if($usuario->foto)
                <img src="{{ Storage::url($usuario->foto) }}" class="w-20 h-20 rounded-full object-cover border-2 border-white/30">
            @else
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-brand-400 to-purple-500 flex items-center justify-center text-3xl font-bold">
                    {{ substr($usuario->nombre, 0, 1) }}
                </div>
            @endif
            <div class="flex-1">
                <h1 class="text-3xl font-bold">{{ $usuario->nombre }} {{ $usuario->apellido }}</h1>
                <p class="text-white/80">{{ $usuario->email }}</p>
                <div class="flex gap-2 mt-2">
                    <span class="bg-blue-400/30 px-3 py-1 rounded-full text-xs capitalize">{{ __('ui.role_labels.' . $usuario->rol) }}</span>
                    <span class="{{ $usuario->estado === 'activo' ? 'bg-green-400/30' : 'bg-red-400/30' }} px-3 py-1 rounded-full text-xs">
                        {{ __('ui.status_labels.' . $usuario->estado) }}
                    </span>
                </div>
            </div>
            <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/15 px-4 py-2 rounded-xl">
                <x-icon name="pencil" class="w-4 h-4" /> {{ __('ui.edit') }}
            </a>
        </div>
    </div>

    @if($usuario->tutor)
    <div class="glass rounded-2xl p-6 text-white">
        <h2 class="text-xl font-bold mb-4 flex items-center"><x-icon name="badge-check" class="w-5 h-5" /> {{ __('ui.tutor_information') }}</h2>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-white/60">{{ __('ui.university') }}</p>
                <p class="font-medium">{{ $usuario->tutor->universidad }}</p>
            </div>
            <div>
                <p class="text-white/60">{{ __('ui.degree') }}</p>
                <p class="font-medium">{{ $usuario->tutor->carrera }}</p>
            </div>
            <div>
                <p class="text-white/60">{{ __('ui.hourly_price') }}</p>
                <p class="font-medium">S/ {{ $usuario->tutor->precio_hora }}</p>
            </div>
            <div>
                <p class="text-white/60">{{ __('ui.approval') }}</p>
                <p class="font-medium capitalize">{{ __('ui.status_labels.' . $usuario->tutor->aprobacion) }}</p>
            </div>
            <div>
                <p class="text-white/60">{{ __('ui.plan') }}</p>
                <p class="font-medium capitalize">{{ __('ui.plan_labels.' . $usuario->tutor->plan) }}</p>
            </div>
            <div>
                <p class="text-white/60">{{ __('ui.rating') }}</p>
                <p class="font-medium flex items-center gap-1.5"><x-icon name="star" class="w-4 h-4 icon-filled" /> {{ $usuario->tutor->calificacion_promedio }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="glass rounded-2xl p-6 text-white">
        <h2 class="text-xl font-bold mb-4 flex items-center"><x-icon name="chart" class="w-5 h-5" /> {{ __('ui.activity') }}</h2>
        <div class="grid grid-cols-3 gap-4 text-center">
            <div>
                <p class="text-3xl font-bold">{{ $usuario->notificaciones->count() }}</p>
                <p class="text-xs text-white/60">{{ __('ui.notifications') }}</p>
            </div>
            <div>
                <p class="text-3xl font-bold">{{ $usuario->created_at->diffForHumans() }}</p>
                <p class="text-xs text-white/60">{{ __('ui.registration') }}</p>
            </div>
            <div>
                <p class="flex justify-center">
                    @if($usuario->email_verified_at)
                        <x-icon name="check-circle" class="w-8 h-8" />
                    @else
                        <x-icon name="x-circle" class="w-8 h-8" />
                    @endif
                </p>
                <p class="text-xs text-white/60">{{ __('ui.verified_email') }}</p>
            </div>
        </div>
    </div>

    <a href="{{ route('admin.usuarios.index') }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/15 px-6 py-3 rounded-xl text-white font-bold transition">
        <x-icon name="arrow-left" class="w-4 h-4" /> {{ __('ui.back_to_list') }}
    </a>
</div>
@endsection