@extends('layouts.app')

@section('title', __('ui.tutor_dashboard'))

@section('content')

<div class="glass rounded-2xl p-6 text-white mb-6"> <h1 class="text-3xl font-bold mb-1">{{ __('ui.tutor_dashboard') }}</h1> <p class="text-white/80 flex items-center gap-2">{{ __('ui.welcome_user', ['name' => Auth::user()->nombre]) }} <x-icon name="graduation-cap" class="w-4 h-4" /></p> </div>

@if(!Auth::user()->tutor)

<div class="glass rounded-2xl p-6 text-white border-l-4 border-blue-400">
    <h2 class="text-xl font-bold mb-2 flex items-center gap-2"><x-icon name="badge-check" class="w-5 h-5" /> {{ __('ui.complete_profile_title') }}</h2>

    <p class="text-white/80 mb-4">
        {{ __('ui.start_receiving_requests') }}
    </p>

    <a href="{{ route('tutor.register.form') }}"
       class="btn-white inline-flex items-center gap-2 px-6 py-3 font-bold">
        {{ __('ui.complete_profile_button') }} <x-icon name="arrow-right" class="w-4 h-4" />
    </a>
</div>

{{-- Opciones que se habilitan con el perfil aprobado --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-6" aria-disabled="true">

    @foreach([
        ['layout-dashboard', 'ui.my_classes', 'ui.accept_or_complete_classes'],
        ['wallet', 'ui.withdrawals', 'ui.request_your_money'],
        ['sparkles', 'ui.subscriptions', 'ui.show_as_featured'],
        ['clock', 'ui.availability', 'ui.set_your_schedule'],
        ['star', 'ui.my_reviews', 'ui.reply_to_students'],
    ] as [$icono, $titulo, $descripcion])

        <div
            class="glass rounded-2xl p-6 text-white pointer-events-none opacity-45 cursor-not-allowed select-none"
            title="{{ __('ui.complete_profile') }}">

            <div class="flex items-start justify-between mb-3">
                <div class="icon-chip">
                    <x-icon :name="$icono" />
                </div>
                <x-icon name="lock" class="w-4 h-4" />
            </div>

            <h3 class="font-bold">{{ __($titulo) }}</h3>

            <p class="text-sm text-white/70">{{ __($descripcion) }}</p>
        </div>

    @endforeach

</div>


@elseif(Auth::user()->tutor->aprobacion === 'pendiente')

<div class="glass rounded-2xl p-6 text-white border-l-4 border-yellow-400">
    <p class="text-lg">
        <strong class="inline-flex items-center gap-2"><x-icon name="clock" class="w-5 h-5" /> {{ __('ui.profile_under_review') }}</strong>
    </p>

    <p class="text-white/80 mt-1">
        {{ __('ui.request_under_review') }}
    </p>
</div>


@elseif(Auth::user()->tutor->aprobacion === 'rechazado')

<div class="glass rounded-2xl p-6 text-white border-l-4 border-red-400">
    <p class="text-lg">
        <strong class="inline-flex items-center gap-2"><x-icon name="x-circle" class="w-5 h-5" /> {{ __('ui.profile_rejected') }}</strong>
    </p>

    <p class="text-white/80 mt-1">
        {{ __('ui.reason') }} {{ Auth::user()->tutor->motivo_rechazo }}
    </p>

    <a href="{{ route('tutor.register.form') }}"
       class="inline-flex items-center gap-2 mt-3 underline text-white">
        {{ __('ui.try_again') }} <x-icon name="arrow-right" class="w-4 h-4" />
    </a>
</div>


@else

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">

    {{-- Mis Clases --}}
    <a href="{{ route('tutor.dashboard') }}"
       class="glass rounded-2xl p-6 text-white hover:bg-white/25 transition group">

        <div class="icon-chip mb-3 group-hover:scale-110 transition">
            <x-icon name="layout-dashboard" />
        </div>

        <h3 class="font-bold">
            {{ __('ui.my_classes') }}
        </h3>

        <p class="text-sm text-white/70">
            {{ __('ui.accept_or_complete_classes') }}
        </p>
    </a>

    {{-- Retiros --}}
    <a href="{{ route('tutor.retiros.index') }}"
       class="glass rounded-2xl p-6 text-white hover:bg-white/25 transition group">

        <div class="icon-chip mb-3 group-hover:scale-110 transition">
            <x-icon name="wallet" />
        </div>

        <h3 class="font-bold">
            {{ __('ui.withdrawals') }}
        </h3>

        <p class="text-sm text-white/70">
            {{ __('ui.request_your_money') }}
        </p>
    </a>

    {{-- Suscripción --}}
    <a href="{{ route('tutor.suscripciones.index') }}"
       class="glass rounded-2xl p-6 text-white hover:bg-white/25 transition group">

        <div class="icon-chip mb-3 group-hover:scale-110 transition">
            <x-icon name="sparkles" />
        </div>

        <h3 class="font-bold">
            {{ __('ui.subscriptions') }}
        </h3>

        <p class="text-sm text-white/70">
            {{ __('ui.show_as_featured') }}
        </p>
    </a>

    {{-- Disponibilidad --}}
    <a href="{{ route('tutor.disponibilidad.index') }}"
       class="glass rounded-2xl p-6 text-white hover:bg-white/25 transition group">

        <div class="icon-chip mb-3 group-hover:scale-110 transition">
            <x-icon name="clock" />
        </div>

        <h3 class="font-bold">
            {{ __('ui.availability') }}
        </h3>

        <p class="text-sm text-white/70">
            {{ __('ui.set_your_schedule') }}
        </p>
    </a>

    {{-- Mis Reseñas --}}
    <a href="{{ route('tutor.resenas.index') }}"
       class="glass rounded-2xl p-6 text-white hover:bg-white/25 transition group">

        <div class="icon-chip mb-3 group-hover:scale-110 transition">
            <x-icon name="star" />
        </div>

        <h3 class="font-bold">
            {{ __('ui.my_reviews') }}
        </h3>

        <p class="text-sm text-white/70">
            {{ __('ui.reply_to_students') }}
        </p>
    </a>

</div>


@endif
@endsection