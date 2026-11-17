@extends('layouts.guest')

@section('title', __('ui.verify_email_title').' - TutorMatch')

@section('bodyClass', 'flex items-center justify-center p-4')

@section('content')
    <div class="glass-card rounded-3xl p-10 w-full max-w-md text-white text-center">
        <div class="icon-chip mx-auto mb-4 !w-16 !h-16 rounded-2xl"><x-icon name="mail" class="!w-7 !h-7" /></div>
        <h1 class="text-2xl font-bold mb-3">{{ __('ui.verify_email_title') }}</h1>
        <p class="text-white/80 mb-6 text-sm">
            {{ __('ui.verification_sent_to') }} <strong>{{ Auth::user()->email }}</strong>.
            {{ __('ui.check_inbox') }}
        </p>

        <x-flash type="success" />

        <x-flash type="error" />

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="bg-[#8b5e3c] hover:bg-[#70482d] text-white w-full py-3 rounded-xl font-bold transition">
                {{ __('ui.resend_email') }}
            </button>
        </form>

        <form method="POST" action="{{ route('verification.skip') }}" class="mt-3">
            @csrf
            <button type="submit" class="w-full py-3 rounded-xl font-bold border border-white/50 text-white hover:bg-white/10 transition">
                {{ __('ui.skip_verification') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="text-white/70 text-sm hover:text-white">
                {{ __('ui.sign_out') }}
            </button>
        </form>
    </div>
@endsection
