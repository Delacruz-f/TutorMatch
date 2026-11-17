@extends('layouts.guest')

@section('title', __('ui.register_title'))

@section('bodyClass', 'flex items-center justify-center p-4 py-8 overflow-x-hidden')

@push('styles')
    <style>
        /* Los estilos de .input-glass y .btn-glass viven en theme.css */
        .register-card {
            max-height: calc(100vh - 2rem);
            overflow-y: auto;
        }
    </style>
@endpush

@section('content')

    <div class="bubble w-64 h-64 top-10 left-10"></div>
    <div class="bubble w-40 h-40 bottom-20 right-20" style="animation-delay: 2s;"></div>

    <div class="register-card glass-card rounded-3xl p-8 sm:p-10 w-full max-w-lg relative z-10 text-white">

        <div class="flex flex-col items-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-white/15 backdrop-blur flex items-center justify-center mb-4 border border-white/20 overflow-hidden">
                <img src="{{ asset('img/TUTORMATCH1.png') }}" alt="TutorMatch" class="w-full h-full object-contain">
            </div>
            <h1 class="text-3xl font-bold">{{ __('ui.create_account') }}</h1>
            <p class="text-white/70 text-sm mt-1">{{ __('ui.join_tutormatch') }}</p>
        </div>

        <x-form-errors blur />

        {{-- BOTÓN GOOGLE --}}
        <a href="{{ route('google.redirect') }}"
           class="flex items-center justify-center gap-3 w-full bg-white text-gray-800 py-3 rounded-xl font-medium hover:bg-gray-100 transition mb-4">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            {{ __('ui.register_google') }}
        </a>

        {{-- Divisor --}}
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-white/20"></div>
            </div>
            <div class="relative flex justify-center text-xs">
                <span class="px-3 bg-transparent text-white/60">{{ __('ui.or_register_email') }}</span>
            </div>
        </div>

        {{-- Formulario tradicional --}}
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('ui.first_name') }}</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" required
                           placeholder="Juan"
                           class="input-glass w-full px-4 py-3 rounded-xl transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('ui.last_name') }}</label>
                    <input type="text" name="apellido" value="{{ old('apellido') }}" required
                           placeholder="Pérez"
                           class="input-glass w-full px-4 py-3 rounded-xl transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">{{ __('ui.email') }}</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       placeholder="{{ __('ui.login_email_placeholder') }}"
                       class="input-glass w-full px-4 py-3 rounded-xl transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('ui.password') }}</label>
                    <input type="password" name="password" required
                           placeholder="••••••••"
                           class="input-glass w-full px-4 py-3 rounded-xl transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('ui.password_confirm') }}</label>
                    <input type="password" name="password_confirmation" required
                           placeholder="••••••••"
                           class="input-glass w-full px-4 py-3 rounded-xl transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">{{ __('ui.register_as') }}</label>
                <select name="rol" class="input-glass w-full px-4 py-3 rounded-xl transition-all">
                    <option value="estudiante" {{ old('rol') == 'estudiante' ? 'selected' : '' }} class="text-gray-800">{{ __('ui.student') }}</option>
                    <option value="tutor" {{ old('rol') == 'tutor' ? 'selected' : '' }} class="text-gray-800">{{ __('ui.tutor') }}</option>
                </select>
            </div>

            <button type="submit"
                    class="btn-glass w-full py-3 rounded-xl font-bold text-base mt-2">
                {{ __('ui.create_my_account') }}
            </button>
        </form>

        <p class="text-center text-sm mt-6 text-white/80">
            {{ __('ui.already_have_account') }}
            <a href="{{ route('login') }}" class="font-bold text-white hover:underline ml-1">{{ __('ui.start_session') }}</a>
        </p>
    </div>

@endsection