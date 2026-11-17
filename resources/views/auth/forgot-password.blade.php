@extends('layouts.guest')

@section('title', __('ui.forgot_password_title').' - TutorMatch')

@section('bodyClass', 'flex items-center justify-center p-4')

{{-- Los estilos de .input-glass viven en theme.css --}}

@section('content')
    <div class="glass-card rounded-3xl p-10 w-full max-w-md text-white">
        <div class="text-center mb-6">
            <div class="icon-chip mx-auto mb-3 !w-14 !h-14 rounded-2xl"><x-icon name="key" class="!w-6 !h-6" /></div>
            <h1 class="text-2xl font-bold">{{ __('ui.forgot_password_title') }}</h1>
            <p class="text-white/70 text-sm mt-2">{{ __('ui.reset_link_message') }}</p>
        </div>

        <x-flash type="success" />

        <x-form-errors />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-2">{{ __('ui.email') }}</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       placeholder="{{ __('ui.login_email_placeholder') }}"
                       class="input-glass w-full px-4 py-3 rounded-xl transition-all">
            </div>
            <button type="submit" class="bg-white text-brand-600 w-full py-3 rounded-xl font-bold hover:bg-white/90 transition">
                {{ __('ui.send_link') }}
            </button>
        </form>

        <p class="text-center text-sm mt-6 text-white/80">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 hover:underline"><x-icon name="arrow-left" class="w-4 h-4" /> {{ __('ui.back_to_login') }}</a>
        </p>
    </div>
@endsection