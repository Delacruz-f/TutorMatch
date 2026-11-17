@extends('layouts.guest')

@section('title', __('ui.welcome_title'))

@section('bodyClass', 'text-white overflow-x-hidden')
@section('languageSwitcherInNavbar', true)

@push('styles')
    <style>
        /* Los estilos base de .glass-card / .btn-white / .btn-yellow viven en theme.css */
        .glass-card {
            transition: transform 260ms ease, border-color 260ms ease, box-shadow 260ms ease;
        }
        .glass-card:hover {
            transform: translateY(-4px);
        }
        /* Burbujas propias de la portada */
        .bubble {
            background: rgba(255, 255, 255, 0.08);
            animation: float 8s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-30px); }
        }
    </style>
@endpush

@section('content')

    <div class="bubble w-96 h-96 top-20 left-10"></div>
    <div class="bubble w-64 h-64 top-1/3 right-10" style="animation-delay: 3s;"></div>
    <div class="bubble w-48 h-48 bottom-20 left-1/3" style="animation-delay: 5s;"></div>

    <!-- NAVBAR -->
    <nav class="glass sticky top-0 z-50 px-4 sm:px-8">
        <div class="max-w-7xl mx-auto flex justify-between items-center h-16">
            <a href="/" class="text-2xl font-bold font-display tracking-tight">TutorMatch</a>
            <div class="flex items-center gap-3">
                @include('partials.language-switcher')
                @auth
                    <span class="hidden sm:inline text-white/90">{{ __('ui.hello', ['name' => auth()->user()->nombre]) }}</span>
                    @php
                        $rutaDash = auth()->user()->rol === 'administrador' ? 'admin.dashboard' :
                                   (auth()->user()->rol === 'tutor' ? 'tutor.dashboard' : 'estudiante.dashboard');
                    @endphp
                    <a href="{{ route($rutaDash) }}" class="btn-white px-5 py-2 rounded-xl font-bold text-sm">{{ __('ui.my_dashboard') }}</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-white/80 hover:text-white text-sm px-3">{{ __('ui.sign_out_short') }}</button>
                    </form>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="text-white/90 hover:text-white px-4 py-2 font-medium text-sm">{{ __('ui.sign_in') }}</a>
                    <a href="{{ route('register') }}" class="btn-white px-5 py-2 rounded-xl font-bold text-sm">{{ __('ui.register') }}</a>
                @endguest
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="relative max-w-7xl mx-auto px-4 sm:px-8 py-20 text-center">
        <div class="glass rounded-3xl p-10 sm:p-16 relative z-10">
            <span class="inline-flex items-center gap-2 bg-white/15 border border-white/20 px-4 py-1.5 rounded-full text-sm mb-6">
                <x-icon name="graduation-cap" class="w-4 h-4" /> {{ __('ui.welcome_badge') }}
            </span>
            <h1 class="text-4xl sm:text-6xl font-extrabold mb-6 leading-tight">
                {{ __('ui.welcome_hero_prefix') }}<br>
                <span class="text-orange-300">{{ __('ui.welcome_hero_highlight') }}</span>
                {{ __('ui.welcome_hero_suffix') }}
            </h1>
            <p class="text-lg sm:text-xl text-white/80 mb-8 max-w-2xl mx-auto">
                {{ __('ui.welcome_description') }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="btn-white inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl font-bold text-lg">
                    <x-icon name="graduation-cap" class="w-5 h-5" /> {{ __('ui.start_as_student') }}
                </a>
                <a href="{{ route('register') }}" class="btn-yellow inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl font-bold text-lg">
                    <x-icon name="user-check" class="w-5 h-5" /> {{ __('ui.become_tutor') }}
                </a>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="max-w-7xl mx-auto px-4 sm:px-8 py-16 relative z-10">
        <h2 class="text-3xl sm:text-4xl font-bold text-center mb-12">{{ __('ui.why_tutormatch') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="glass-card rounded-3xl p-8 text-center">
                <div class="icon-chip mx-auto mb-4 !w-16 !h-16 rounded-2xl"><x-icon name="search" class="!w-7 !h-7" /></div>
                <h3 class="text-xl font-bold mb-3">{{ __('ui.smart_search') }}</h3>
                <p class="text-white/80">{{ __('ui.smart_search_description') }}</p>
            </div>
            <div class="glass-card rounded-3xl p-8 text-center">
                <div class="icon-chip mx-auto mb-4 !w-16 !h-16 rounded-2xl"><x-icon name="video" class="!w-7 !h-7" /></div>
                <h3 class="text-xl font-bold mb-3">{{ __('ui.virtual_classes') }}</h3>
                <p class="text-white/80">{{ __('ui.virtual_classes_description') }}</p>
            </div>
            <div class="glass-card rounded-3xl p-8 text-center">
                <div class="icon-chip mx-auto mb-4 !w-16 !h-16 rounded-2xl"><x-icon name="badge-check" class="!w-7 !h-7" /></div>
                <h3 class="text-xl font-bold mb-3">{{ __('ui.verified_tutors') }}</h3>
                <p class="text-white/80">{{ __('ui.verified_tutors_description') }}</p>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="glass mt-12 py-6 text-center text-white/70 text-sm">
        &copy; {{ date('Y') }} TutorMatch — {{ __('ui.all_rights_reserved') }}
    </footer>

@endsection
