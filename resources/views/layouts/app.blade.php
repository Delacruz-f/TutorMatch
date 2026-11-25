<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.seo')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/js/app.js')

    <title>TutorMatch - @yield('title', __('ui.home'))</title>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        display: ['Sora', 'Plus Jakarta Sans', 'sans-serif'],
                    },

                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        },
                    },

                    animation: {
                        gradient: 'gradient 15s ease infinite',
                        float: 'float 6s ease-in-out infinite',
                    },

                    keyframes: {
                        gradient: {
                            '0%, 100%': {
                                backgroundPosition: '0% 50%'
                            },
                            '50%': {
                                backgroundPosition: '100% 50%'
                            },
                        },

                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0px)'
                            },
                            '50%': {
                                transform: 'translateY(-10px)'
                            },
                        },
                    },
                },
            },
        };
    </script>

    <style>
        /*
            Base del panel.
            El fondo, el vidrio esmerilado y los estilos de .glass /.input-glass /
            .btn-primary viven en theme.css (se carga desde partials/seo), para
            no duplicar reglas que allí ya estan definidas con el tema oscuro.
            Aquí solo quedan los ajustes propios del layout del panel.
        */

        /* Sidebar item activo (el material base vive en theme.css) */
        .sidebar-item-active {
            border-left: 3px solid #fdba74;
            color: #fff;
        }

        /* Scrollbar custom */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

    </style>

    @stack('styles')
</head>

<body
    class="font-sans antialiased min-h-screen text-white"
    data-user-id="{{ Auth::id() ?? '' }}"
    data-broadcast-auth-url="{{ url('/broadcasting/auth') }}">

<div x-data="{ sidebarOpen: false }" class="min-h-screen flex">

    <!-- OVERLAY MÓVIL -->
    <div
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        x-transition.opacity
        class="fixed inset-0 bg-black/40 z-40 lg:hidden">
    </div>

    <!-- SIDEBAR -->
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="sidebar fixed lg:translate-x-0 top-0 left-0 h-screen w-64 glass z-50 transition-transform duration-300 ease-in-out flex flex-col">

        <!-- LOGO -->
        <div class="p-6 border-b border-white/20">

            <a
                href="{{ route('home') }}"
                class="flex items-center gap-3 text-white">

                <div class="w-10 h-10 bg-white/15 rounded-xl flex items-center justify-center overflow-hidden border border-white/15">
                    <img src="{{ asset('img/TUTORMATCH1.png') }}" alt="TutorMatch" class="w-full h-full object-contain">
                </div>

                <div>
                    <h1 class="text-lg font-bold font-display tracking-tight">
                        TutorMatch
                    </h1>

                    <p class="text-xs text-white/70">
                        {{ __('ui.education_platform') }}
                    </p>
                </div>

            </a>

        </div>

        <!-- NAVEGACIÓN -->
        <nav class="flex-1 overflow-y-auto p-4 space-y-1">

            @auth

                @php
                    $rutaActual = request()->route()->getName();

                    /*
                        Un tutor que aún no tiene perfil (o lo está completando / estaba
                        rechazado) no puede usar el resto de su panel: solo se habilita
                        cuando el perfil está aprobado.
                    */
                    $perfilTutor = Auth::user()->rol === 'tutor' ? Auth::user()->tutor : null;
                    $panelTutorBloqueado = Auth::user()->rol === 'tutor'
                        && (! $perfilTutor || in_array($perfilTutor->aprobacion, ['pendiente', 'rechazado']));

                    $itemBloqueado = 'pointer-events-none opacity-45 cursor-not-allowed select-none';
                @endphp

                <!-- INICIO -->

                <a
                    href="{{ route('home') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ $rutaActual === 'home' ? 'sidebar-item-active' : '' }}">

                    <x-icon name="home" class="w-5 h-5 shrink-0" />
                    <span class="font-medium">{{ __('ui.home') }}</span>

                </a>


                <!-- BÚSQUEDA -->

                <a
                    href="{{ route('busqueda.index') }}"
                    @if($panelTutorBloqueado) aria-disabled="true" tabindex="-1" title="{{ __('ui.complete_profile') }}" @endif
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'busqueda') ? 'sidebar-item-active' : '' }} {{ $panelTutorBloqueado ? $itemBloqueado : '' }}">

                    <x-icon name="search" class="w-5 h-5 shrink-0" />
                    <span class="font-medium">{{ __('ui.search_tutors') }}</span>

                </a>


                {{-- =========================================================
                    MENÚ ADMIN
                ========================================================== --}}

                @if(Auth::user()->rol === 'administrador')

                    <p class="px-4 pt-4 pb-2 text-xs uppercase text-white/50 font-bold tracking-wider">
                        {{ __('ui.administration') }}
                    </p>


                    <!-- DASHBOARD -->

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ $rutaActual === 'admin.dashboard' ? 'sidebar-item-active' : '' }}">

                        <x-icon name="layout-dashboard" class="w-5 h-5 shrink-0" />
                        <span class="font-medium">{{ __('ui.dashboard') }}</span>

                    </a>


                    <!-- TUTORES -->

                    <a
                        href="{{ route('admin.tutores.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'admin.tutores') ? 'sidebar-item-active' : '' }}">

                        <x-icon name="graduation-cap" class="w-5 h-5 shrink-0" />
                        <span class="font-medium">{{ __('ui.tutors') }}</span>

                    </a>


                    <!-- USUARIOS -->

                    <a
                        href="{{ route('admin.usuarios.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'admin.usuarios') ? 'sidebar-item-active' : '' }}">

                        <x-icon name="users" class="w-5 h-5 shrink-0" />
                        <span class="font-medium">{{ __('ui.users') }}</span>

                    </a>


                    <!-- MATERIAS -->

                    <a
                        href="{{ route('admin.materias.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'admin.materias') ? 'sidebar-item-active' : '' }}">

                        <x-icon name="book-open" class="w-5 h-5 shrink-0" />
                        <span class="font-medium">{{ __('ui.subjects') }}</span>

                    </a>


                    <!-- PAGOS -->

                    <a
                        href="{{ route('admin.pagos.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'admin.pagos') ? 'sidebar-item-active' : '' }}">

                        <x-icon name="credit-card" class="w-5 h-5 shrink-0" />
                        <span class="font-medium">{{ __('ui.payments') }}</span>

                    </a>


                    <!-- RETIROS -->

                    <a
                        href="{{ route('admin.retiros.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'admin.retiros') ? 'sidebar-item-active' : '' }}">

                        <x-icon name="wallet" class="w-5 h-5 shrink-0" />
                        <span class="font-medium">{{ __('ui.withdrawals') }}</span>

                    </a>


                    <!-- RESEÑAS -->

                    <a
                        href="{{ route('admin.resenas.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'admin.resenas') ? 'sidebar-item-active' : '' }}">

                        <x-icon name="star" class="w-5 h-5 shrink-0" />
                        <span class="font-medium">{{ __('ui.reviews') }}</span>

                    </a>
                    <a href="{{ route('admin.transacciones.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'admin.transacciones') ? 'sidebar-item-active' : '' }}">
    <x-icon name="receipt" class="w-5 h-5 shrink-0" /><span class="font-medium">{{ __('ui.transactions') }}</span>
</a>


                    <!-- SUSCRIPCIONES -->

                    <a
                        href="{{ route('admin.suscripciones.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'admin.suscripciones') ? 'sidebar-item-active' : '' }}">

                        <x-icon name="sparkles" class="w-5 h-5 shrink-0" />
                        <span class="font-medium">{{ __('ui.subscriptions') }}</span>

                    </a>


                    <!-- REPORTES -->

                    <a
                        href="{{ route('admin.reportes.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'admin.reportes') ? 'sidebar-item-active' : '' }}">

                        <x-icon name="chart" class="w-5 h-5 shrink-0" />
                        <span class="font-medium">{{ __('ui.reports') }}</span>

                    </a>

                @endif


                {{-- =========================================================
                    MENÚ TUTOR
                ========================================================== --}}

                @if(Auth::user()->rol === 'tutor')

                    <p class="px-4 pt-4 pb-2 text-xs uppercase text-white/50 font-bold tracking-wider">
                        {{ __('ui.my_panel') }}
                    </p>


                    <!-- DASHBOARD -->

                    <a
                        href="{{ route('tutor.dashboard') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ $rutaActual === 'tutor.dashboard' ? 'sidebar-item-active' : '' }}">

                        <x-icon name="layout-dashboard" class="w-5 h-5 shrink-0" />
                        <span class="font-medium">{{ __('ui.dashboard') }}</span>

                    </a>

                    @if($panelTutorBloqueado)

                        <p class="mx-1 mt-2 mb-1 px-3 py-2.5 rounded-xl text-xs leading-relaxed text-white/70 bg-white/10 border border-white/15 flex gap-2">
                            <x-icon name="lock" class="w-4 h-4 shrink-0 mt-0.5" />
                            <span>{{ __('ui.profile_pending') }}</span>
                        </p>

                    @endif


                    <!-- RETIROS -->

                    <a
                        href="{{ route('tutor.retiros.index') }}"
                        @if($panelTutorBloqueado) aria-disabled="true" tabindex="-1" title="{{ __('ui.complete_profile') }}" @endif
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'tutor.retiros') ? 'sidebar-item-active' : '' }} {{ $panelTutorBloqueado ? $itemBloqueado : '' }}">

                        <x-icon name="wallet" class="w-5 h-5 shrink-0" />
                        <span class="font-medium">{{ __('ui.withdrawals') }}</span>

                    </a>


                    <!-- SUSCRIPCIÓN -->

                    <a
                        href="{{ route('tutor.suscripciones.index') }}"
                        @if($panelTutorBloqueado) aria-disabled="true" tabindex="-1" title="{{ __('ui.complete_profile') }}" @endif
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'tutor.suscripciones') ? 'sidebar-item-active' : '' }} {{ $panelTutorBloqueado ? $itemBloqueado : '' }}">

                        <x-icon name="sparkles" class="w-5 h-5 shrink-0" />
                        <span class="font-medium">{{ __('ui.subscriptions') }}</span>

                    </a>


                    <!-- DISPONIBILIDAD -->

                    <a
                        href="{{ route('tutor.disponibilidad.index') }}"
                        @if($panelTutorBloqueado) aria-disabled="true" tabindex="-1" title="{{ __('ui.complete_profile') }}" @endif
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'tutor.disponibilidad') ? 'sidebar-item-active' : '' }} {{ $panelTutorBloqueado ? $itemBloqueado : '' }}">

                        <x-icon name="clock" class="w-5 h-5 shrink-0" />
                        <span class="font-medium">{{ __('ui.availability') }}</span>

                    </a>


                    <!-- MIS RESEÑAS -->

                    <a
                        href="{{ route('tutor.resenas.index') }}"
                        @if($panelTutorBloqueado) aria-disabled="true" tabindex="-1" title="{{ __('ui.complete_profile') }}" @endif
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'tutor.resenas') ? 'sidebar-item-active' : '' }} {{ $panelTutorBloqueado ? $itemBloqueado : '' }}">

                        <x-icon name="star" class="w-5 h-5 shrink-0" />
                        <span class="font-medium">{{ __('ui.my_reviews') }}</span>

                    </a>

                @endif


                {{-- =========================================================
                    MENÚ ESTUDIANTE
                ========================================================== --}}

                @if(Auth::user()->rol === 'estudiante')

                    <p class="px-4 pt-4 pb-2 text-xs uppercase text-white/50 font-bold tracking-wider">
                        {{ __('ui.my_panel') }}
                    </p>


                    <!-- MIS CLASES -->

                    <a
                        href="{{ route('estudiante.dashboard') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ $rutaActual === 'estudiante.dashboard' ? 'sidebar-item-active' : '' }}">

                        <x-icon name="layout-dashboard" class="w-5 h-5 shrink-0" />
                        <span class="font-medium">{{ __('ui.my_classes') }}</span>

                    </a>

                    <a
                        href="{{ route('pagos.historial') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ $rutaActual === 'pagos.historial' ? 'sidebar-item-active' : '' }}">

                        <x-icon name="receipt" class="w-5 h-5 shrink-0" />
                        <span class="font-medium">{{ __('ui.payment_history') }}</span>

                    </a>

                @endif


                {{-- =========================================================
                    GENERAL
                ========================================================== --}}

                <p class="px-4 pt-4 pb-2 text-xs uppercase text-white/50 font-bold tracking-wider">
                    {{ __('ui.account') }}
                </p>


                <!-- NOTIFICACIONES -->

                <a
                    href="{{ route('notificaciones.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'notificaciones') ? 'sidebar-item-active' : '' }}">

                    <x-icon name="bell" class="w-5 h-5 shrink-0" />
                    <span class="font-medium">{{ __('ui.notifications') }}</span>

                    @php
                        $notifNoLeidas = Auth::user()->notificacionesNoLeidas()->count();
                    @endphp

                    <span
                        data-notification-count
                        data-count-limit="99"
                        class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-0.5 {{ $notifNoLeidas > 0 ? '' : 'hidden' }}">
                        {{ $notifNoLeidas > 99 ? '99+' : $notifNoLeidas }}
                    </span>

                </a>

            @else

                <!-- INICIAR SESIÓN -->

                <a
                    href="{{ route('login') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition">

                    <x-icon name="key" class="w-5 h-5 shrink-0" />
                    <span class="font-medium">{{ __('ui.sign_in') }}</span>

                </a>


                <!-- REGISTRARSE -->

                <a
                    href="{{ route('register') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition">

                    <x-icon name="sparkles" class="w-5 h-5 shrink-0" />
                    <span class="font-medium">{{ __('ui.register') }}</span>

                </a>

            @endauth

        </nav>


        <!-- FOOTER SIDEBAR -->

        <div class="p-4 border-t border-white/20">

            <p class="text-xs text-white/50 text-center">
                &copy; {{ date('Y') }} TutorMatch
            </p>

        </div>

    </aside>


    <!-- CONTENIDO PRINCIPAL -->

    <div class="w-full min-w-0 flex-1 lg:ml-64 flex flex-col min-h-screen">


        <!-- TOPBAR -->

        <header class="glass-dark sticky top-0 z-30 px-4 sm:px-6 py-3">

            <div class="flex items-center justify-between gap-2 sm:gap-4">

                {{-- El badge de notificaciones del topbar usa $notifNoLeidas --}}
                @auth
                    @php
                        $notifNoLeidas = $notifNoLeidas ?? Auth::user()->notificacionesNoLeidas()->count();
                    @endphp
                @endauth

                <!-- HAMBURGER + TÍTULO -->

                <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3">

                    <button
                        @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden text-white p-2 hover:bg-white/10 rounded-lg">

                        <x-icon name="menu" class="w-6 h-6" />

                    </button>


                    <h2 class="min-w-0 truncate text-white font-semibold text-base sm:text-lg">
                        @yield('title', 'TutorMatch')
                    </h2>

                </div>


                <!-- ACCIONES DERECHA -->

                <div class="flex shrink-0 items-center gap-1 sm:gap-3">

                    @include('partials.language-switcher')

                    @auth

                        <!-- NOTIFICACIONES -->

                        <a
                            href="{{ route('notificaciones.index') }}"
                            class="relative text-white p-2 hover:bg-white/10 rounded-lg transition">

                            <x-icon name="bell" class="w-5 h-5" />


                            <span
                                data-notification-count
                                data-count-limit="9"
                                class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold rounded-full w-4 h-4 flex items-center justify-center {{ $notifNoLeidas > 0 ? '' : 'hidden' }}">
                                {{ $notifNoLeidas > 9 ? '9+' : $notifNoLeidas }}
                            </span>

                        </a>


                        <!-- PERFIL DROPDOWN -->

                        <div
                            x-data="{ open: false }"
                            class="relative">

                            <button
                                @click="open = !open"
                                class="flex items-center gap-2 text-white p-1 hover:bg-white/10 rounded-lg transition">

                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-400 to-purple-500 flex items-center justify-center text-white font-bold">

                                    {{ substr(Auth::user()->nombre, 0, 1) }}

                                </div>


                                <div class="hidden sm:block text-left">

                                    <p class="text-sm font-medium">
                                        {{ Auth::user()->nombre }}
                                    </p>

                                    <p class="text-xs text-white/70 capitalize">
                                        {{ __('ui.role_labels.' . Auth::user()->rol) }}
                                    </p>

                                </div>


                                <x-icon name="chevron-down" class="w-4 h-4" />

                            </button>


                            <!-- DROPDOWN -->

                            <div
                                x-show="open"
                                @click.outside="open = false"
                                x-transition
                                class="absolute right-0 mt-2 w-48 glass rounded-xl py-2 text-white shadow-xl">


                                <!-- MI PERFIL -->

                                <a
                                    href="{{ route('perfil.edit') }}"
                                    class="flex items-center gap-2.5 px-4 py-2 text-sm hover:bg-white/10">

                                    <x-icon name="user" class="w-4 h-4" /> {{ __('ui.my_profile') }}

                                </a>


                                <!-- NOTIFICACIONES -->

                                <a
                                    href="{{ route('notificaciones.index') }}"
                                    class="flex items-center gap-2.5 px-4 py-2 text-sm hover:bg-white/10">

                                    <x-icon name="bell" class="w-4 h-4" /> {{ __('ui.notifications') }}

                                </a>


                                <hr class="border-white/10 my-1">


                                <!-- LOGOUT -->

                                <form
                                    method="POST"
                                    action="{{ route('logout') }}">

                                    @csrf

                                    <button
                                        type="submit"
                                        class="w-full inline-flex items-center gap-2.5 text-left px-4 py-2 text-sm text-red-200 hover:bg-white/10">

                                        <x-icon name="log-out" class="w-4 h-4" /> {{ __('ui.sign_out') }}

                                    </button>

                                </form>

                            </div>

                        </div>

                    @else

                        <!-- LOGIN -->

                        <a
                            href="{{ route('login') }}"
                            class="hidden sm:inline-flex text-white hover:bg-white/10 px-4 py-2 rounded-lg transition text-sm">

                            {{ __('ui.sign_in') }}

                        </a>


                        <!-- REGISTRO -->

                        <a
                            href="{{ route('register') }}"
                            class="hidden sm:inline-flex btn-primary text-white px-4 py-2 rounded-lg text-sm font-medium">

                            {{ __('ui.register') }}

                        </a>

                    @endauth

                </div>

            </div>

        </header>


        <!-- ALERTAS -->

        <div class="px-4 sm:px-6 pt-4">

            <x-alert />

        </div>


        <!-- CONTENIDO -->

        <main class="w-full min-w-0 flex-1 p-4 sm:p-6">

            @yield('content')

        </main>


        <!-- FOOTER -->

        <footer class="px-6 py-4 text-center text-white/60 text-xs">

            &copy; {{ date('Y') }} TutorMatch — {{ __('ui.all_rights_reserved') }}

        </footer>

    </div>

</div>

<div
    data-notification-toast
    role="status"
    aria-live="polite"
    class="hidden fixed top-20 right-4 z-50 w-full max-w-sm rounded-xl border border-white/20 bg-slate-900 p-4 text-white shadow-xl">
    <a href="{{ route('notificaciones.index') }}" class="block">
        <p data-notification-title class="font-semibold"></p>
        <p data-notification-message class="mt-1 text-sm text-white/75"></p>
    </a>
</div>

@stack('scripts')

</body>
</html>
