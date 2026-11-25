@extends('layouts.guest')

@section('title', __('ui.new_password').' - TutorMatch')

@section('bodyClass', 'flex items-center justify-center p-4')

{{-- Los estilos de .input-glass viven en theme.css --}}

@section('content')
    <div class="glass-card rounded-3xl p-10 w-full max-w-md text-white">
        <div class="text-center mb-6">
            <div class="icon-chip mx-auto mb-3 !w-14 !h-14 rounded-2xl"><x-icon name="lock" class="!w-6 !h-6" /></div>
            <h1 class="text-2xl font-bold">{{ __('ui.new_password') }}</h1>
        </div>

        <x-form-errors />

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="block text-sm font-medium mb-2">{{ __('ui.email') }}</label>
                <input type="email" name="email" value="{{ old('email', request()->email) }}" required
                       class="input-glass w-full px-4 py-3 rounded-xl">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">{{ __('ui.new_password') }}</label>
                <input type="password" name="password" required
                       class="input-glass w-full px-4 py-3 rounded-xl">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">{{ __('ui.confirm_password') }}</label>
                <input type="password" name="password_confirmation" required
                       class="input-glass w-full px-4 py-3 rounded-xl">
            </div>

            <button type="submit" class="bg-white text-brand-600 w-full py-3 rounded-xl font-bold hover:bg-white/90 transition">
                {{ __('ui.reset_password') }}
            </button>
        </form>
    </div>
@endsection