@extends('layouts.guest')

@section('title', __('ui.choose_role_title'))

@section('bodyClass', 'flex items-center justify-center p-4')

@push('styles')
    <style>
        .rol-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .rol-card:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(255, 255, 255, 0.3);
        }
        .rol-card.selected {
            background: rgba(255, 255, 255, 0.35);
            border-color: white;
        }
    </style>
@endpush

@section('content')

    <div class="glass-card rounded-3xl p-8 sm:p-10 w-full max-w-2xl text-white" x-data="{ rolSeleccionado: '' }">
        
        <!-- Header -->
        <div class="text-center mb-8">
            @if($datos['foto_url'])
                <img src="{{ $datos['foto_url'] }}" class="w-20 h-20 rounded-full mx-auto mb-3 border-4 border-white/40">
            @endif
            <h1 class="text-3xl font-bold mb-2">{{ __('ui.welcome_user', ['name' => $datos['nombre']]) }}</h1>
            <p class="text-white/80">{{ $datos['email'] }}</p>
            <p class="text-white/70 text-sm mt-2">{{ __('ui.choose_role_prompt') }}</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('google.registrar') }}">
            @csrf
            <input type="hidden" name="rol" x-model="rolSeleccionado">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                
                <!-- Estudiante -->
                <div @click="rolSeleccionado = 'estudiante'"
                     :class="rolSeleccionado === 'estudiante' ? 'rol-card selected rounded-2xl p-6 text-center' : 'rol-card rounded-2xl p-6 text-center'">
                    <div class="icon-chip mx-auto mb-3 !w-14 !h-14 rounded-2xl"><x-icon name="graduation-cap" class="!w-6 !h-6" /></div>
                    <h3 class="text-xl font-bold mb-2">{{ __('ui.i_am_student') }}</h3>
                    <p class="text-sm text-white/80 leading-relaxed">
                        {{ __('ui.student_role_description') }}
                    </p>
                    <div class="mt-4 text-xs text-white/70 space-y-1">
                        <p class="flex items-center justify-center gap-1.5"><x-icon name="check" class="w-3.5 h-3.5" /> {{ __('ui.find_tutors') }}</p>
                        <p class="flex items-center justify-center gap-1.5"><x-icon name="check" class="w-3.5 h-3.5" /> {{ __('ui.book_classes') }}</p>
                        <p class="flex items-center justify-center gap-1.5"><x-icon name="check" class="w-3.5 h-3.5" /> {{ __('ui.rate_and_review') }}</p>
                    </div>
                </div>

                <!-- Tutor -->
                <div @click="rolSeleccionado = 'tutor'"
                     :class="rolSeleccionado === 'tutor' ? 'rol-card selected rounded-2xl p-6 text-center' : 'rol-card rounded-2xl p-6 text-center'">
                    <div class="icon-chip mx-auto mb-3 !w-14 !h-14 rounded-2xl"><x-icon name="graduation-cap" class="!w-6 !h-6" /></div>
                    <h3 class="text-xl font-bold mb-2">{{ __('ui.i_am_tutor') }}</h3>
                    <p class="text-sm text-white/80 leading-relaxed">
                        {{ __('ui.tutor_role_description') }}
                    </p>
                    <div class="mt-4 text-xs text-white/70 space-y-1">
                        <p class="flex items-center justify-center gap-1.5"><x-icon name="check" class="w-3.5 h-3.5" /> {{ __('ui.upload_documents') }}</p>
                        <p class="flex items-center justify-center gap-1.5"><x-icon name="check" class="w-3.5 h-3.5" /> {{ __('ui.availability') }}</p>
                        <p class="flex items-center justify-center gap-1.5"><x-icon name="check" class="w-3.5 h-3.5" /> {{ __('ui.receive_payments') }}</p>
                    </div>
                </div>
            </div>

            <button type="submit"
                    :disabled="!rolSeleccionado"
                    :class="rolSeleccionado ? 'bg-white text-brand-600 hover:bg-white/90' : 'bg-white/20 cursor-not-allowed'"
                    class="w-full py-3 rounded-xl font-bold text-base transition">
                <span x-show="!rolSeleccionado">{{ __('ui.choose_role_to_continue') }}</span>
                <span x-show="rolSeleccionado" x-text="rolSeleccionado === 'estudiante' ? @js(__('ui.continue_as_student')) : @js(__('ui.continue_as_tutor'))"></span>
            </button>
        </form>

        <p class="text-center text-xs text-white/60 mt-6">
            {{ __('ui.terms_acceptance') }}
        </p>
    </div>

@endsection