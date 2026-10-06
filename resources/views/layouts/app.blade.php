<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TutorMatch - @yield('title', 'Inicio')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
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
        /* Fondo global con gradiente animado */
        body {
            background: linear-gradient(
                -45deg,
                #667eea,
                #764ba2,
                #6366f1,
                #8b5cf6
            );

            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            min-height: 100vh;
        }

        /* Vidrio esmerilado */
        .glass {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
        }

        .glass-dark {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }

        /* Sidebar item activo */
        .sidebar-item-active {
            background: rgba(255, 255, 255, 0.25);
            border-left: 3px solid white;
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

        /* Inputs con estilo glass */
        .input-glass {
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
        }

        .input-glass:focus {
            background: rgba(255, 255, 255, 0.95);
            border-color: #6366f1;
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        /* Botón principal */
        .btn-primary {
            background: linear-gradient(
                135deg,
                #6366f1 0%,
                #8b5cf6 100%
            );

            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4);
        }
    </style>
</head>

<body class="font-sans antialiased min-h-screen text-gray-800">

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
        class="fixed lg:translate-x-0 top-0 left-0 h-screen w-64 glass z-50 transition-transform duration-300 ease-in-out flex flex-col">

        <!-- LOGO -->
        <div class="p-6 border-b border-white/20">

            <a
                href="{{ route('home') }}"
                class="flex items-center gap-3 text-white">

                <div class="w-10 h-10 bg-white/25 rounded-xl flex items-center justify-center font-bold text-xl">
                    T
                </div>

                <div>
                    <h1 class="text-lg font-bold">
                        TutorMatch
                    </h1>

                    <p class="text-xs text-white/70">
                        Plataforma Educativa
                    </p>
                </div>

            </a>

        </div>

        <!-- NAVEGACIÓN -->
        <nav class="flex-1 overflow-y-auto p-4 space-y-1">

            @auth

                @php
                    $rutaActual = request()->route()->getName();
                @endphp

                <!-- INICIO -->

                <a
                    href="{{ route('home') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ $rutaActual === 'home' ? 'sidebar-item-active' : '' }}">

                    <span class="text-lg">🏠</span>
                    <span class="font-medium">Inicio</span>

                </a>


                <!-- BÚSQUEDA -->

                <a
                    href="{{ route('busqueda.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'busqueda') ? 'sidebar-item-active' : '' }}">

                    <span class="text-lg">🔍</span>
                    <span class="font-medium">Buscar Tutores</span>

                </a>


                {{-- =========================================================
                    MENÚ ADMIN
                ========================================================== --}}

                @if(Auth::user()->rol === 'administrador')

                    <p class="px-4 pt-4 pb-2 text-xs uppercase text-white/50 font-bold tracking-wider">
                        Administración
                    </p>


                    <!-- DASHBOARD -->

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ $rutaActual === 'admin.dashboard' ? 'sidebar-item-active' : '' }}">

                        <span class="text-lg">📊</span>
                        <span class="font-medium">Dashboard</span>

                    </a>


                    <!-- TUTORES -->

                    <a
                        href="{{ route('admin.tutores.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'admin.tutores') ? 'sidebar-item-active' : '' }}">

                        <span class="text-lg">👨‍🏫</span>
                        <span class="font-medium">Tutores</span>

                    </a>


                    <!-- USUARIOS -->

                    <a
                        href="{{ route('admin.usuarios.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'admin.usuarios') ? 'sidebar-item-active' : '' }}">

                        <span class="text-lg">👥</span>
                        <span class="font-medium">Usuarios</span>

                    </a>


                    <!-- MATERIAS -->

                    <a
                        href="{{ route('admin.materias.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'admin.materias') ? 'sidebar-item-active' : '' }}">

                        <span class="text-lg">📚</span>
                        <span class="font-medium">Materias</span>

                    </a>


                    <!-- PAGOS -->

                    <a
                        href="{{ route('admin.pagos.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'admin.pagos') ? 'sidebar-item-active' : '' }}">

                        <span class="text-lg">💳</span>
                        <span class="font-medium">Pagos</span>

                    </a>


                    <!-- RETIROS -->

                    <a
                        href="{{ route('admin.retiros.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'admin.retiros') ? 'sidebar-item-active' : '' }}">

                        <span class="text-lg">💰</span>
                        <span class="font-medium">Retiros</span>

                    </a>


                    <!-- RESEÑAS -->

                    <a
                        href="{{ route('admin.resenas.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'admin.resenas') ? 'sidebar-item-active' : '' }}">

                        <span class="text-lg">⭐</span>
                        <span class="font-medium">Reseñas</span>

                    </a>
                    <a href="{{ route('admin.transacciones.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'admin.transacciones') ? 'sidebar-item-active' : '' }}">
    <span class="text-lg">💸</span><span class="font-medium">Transacciones</span>
</a>


                    <!-- SUSCRIPCIONES -->

                    <a
                        href="{{ route('admin.suscripciones.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'admin.suscripciones') ? 'sidebar-item-active' : '' }}">

                        <span class="text-lg">⭐</span>
                        <span class="font-medium">Suscripciones</span>

                    </a>


                    <!-- REPORTES -->

                    <a
                        href="{{ route('admin.reportes.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'admin.reportes') ? 'sidebar-item-active' : '' }}">

                        <span class="text-lg">📈</span>
                        <span class="font-medium">Reportes</span>

                    </a>

                @endif


                {{-- =========================================================
                    MENÚ TUTOR
                ========================================================== --}}

                @if(Auth::user()->rol === 'tutor')

                    <p class="px-4 pt-4 pb-2 text-xs uppercase text-white/50 font-bold tracking-wider">
                        Mi Panel
                    </p>


                    <!-- DASHBOARD -->

                    <a
                        href="{{ route('tutor.dashboard') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ $rutaActual === 'tutor.dashboard' ? 'sidebar-item-active' : '' }}">

                        <span class="text-lg">📊</span>
                        <span class="font-medium">Dashboard</span>

                    </a>


                    <!-- RETIROS -->

                    <a
                        href="{{ route('tutor.retiros.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'tutor.retiros') ? 'sidebar-item-active' : '' }}">

                        <span class="text-lg">💰</span>
                        <span class="font-medium">Retiros</span>

                    </a>


                    <!-- SUSCRIPCIÓN -->

                    <a
                        href="{{ route('tutor.suscripciones.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'tutor.suscripciones') ? 'sidebar-item-active' : '' }}">

                        <span class="text-lg">⭐</span>
                        <span class="font-medium">Suscripción</span>

                    </a>


                    <!-- DISPONIBILIDAD -->

                    <a
                        href="{{ route('tutor.disponibilidad.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'tutor.disponibilidad') ? 'sidebar-item-active' : '' }}">

                        <span class="text-lg">🕐</span>
                        <span class="font-medium">Disponibilidad</span>

                    </a>


                    <!-- MIS RESEÑAS -->

                    <a
                        href="{{ route('tutor.resenas.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'tutor.resenas') ? 'sidebar-item-active' : '' }}">

                        <span class="text-lg">⭐</span>
                        <span class="font-medium">Mis Reseñas</span>

                    </a>

                @endif


                {{-- =========================================================
                    MENÚ ESTUDIANTE
                ========================================================== --}}

                @if(Auth::user()->rol === 'estudiante')

                    <p class="px-4 pt-4 pb-2 text-xs uppercase text-white/50 font-bold tracking-wider">
                        Mi Panel
                    </p>


                    <!-- MIS CLASES -->

                    <a
                        href="{{ route('estudiante.dashboard') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ $rutaActual === 'estudiante.dashboard' ? 'sidebar-item-active' : '' }}">

                        <span class="text-lg">📊</span>
                        <span class="font-medium">Mis Clases</span>

                    </a>

                @endif


                {{-- =========================================================
                    GENERAL
                ========================================================== --}}

                <p class="px-4 pt-4 pb-2 text-xs uppercase text-white/50 font-bold tracking-wider">
                    Cuenta
                </p>


                <!-- NOTIFICACIONES -->

                <a
                    href="{{ route('notificaciones.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition {{ Str::startsWith($rutaActual, 'notificaciones') ? 'sidebar-item-active' : '' }}">

                    <span class="text-lg">🔔</span>
                    <span class="font-medium">Notificaciones</span>

                    @php
                        $notifNoLeidas = Auth::user()->notificacionesNoLeidas()->count();
                    @endphp

                    @if($notifNoLeidas > 0)

                        <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-0.5">
                            {{ $notifNoLeidas > 99 ? '99+' : $notifNoLeidas }}
                        </span>

                    @endif

                </a>

            @else

                <!-- INICIAR SESIÓN -->

                <a
                    href="{{ route('login') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition">

                    <span class="text-lg">🔑</span>
                    <span class="font-medium">Iniciar Sesión</span>

                </a>


                <!-- REGISTRARSE -->

                <a
                    href="{{ route('register') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/90 hover:bg-white/15 transition">

                    <span class="text-lg">✨</span>
                    <span class="font-medium">Registrarse</span>

                </a>

            @endauth

        </nav>


        <!-- FOOTER SIDEBAR -->

        <div class="p-4 border-t border-white/20">

            <p class="text-xs text-white/60 text-center">
                &copy; {{ date('Y') }} TutorMatch
            </p>

        </div>

    </aside>


    <!-- CONTENIDO PRINCIPAL -->

    <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">


        <!-- TOPBAR -->

        <header class="glass-dark sticky top-0 z-30 px-4 sm:px-6 py-3">

            <div class="flex items-center justify-between gap-4">


                <!-- HAMBURGER + TÍTULO -->

                <div class="flex items-center gap-3">

                    <button
                        @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden text-white p-2 hover:bg-white/10 rounded-lg">

                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"/>

                        </svg>

                    </button>


                    <h2 class="text-white font-semibold text-lg">
                        @yield('title', 'TutorMatch')
                    </h2>

                </div>


                <!-- ACCIONES DERECHA -->

                <div class="flex items-center gap-3">

                    @auth

                        <!-- NOTIFICACIONES -->

                        <a
                            href="{{ route('notificaciones.index') }}"
                            class="relative text-white p-2 hover:bg-white/10 rounded-lg transition">

                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>

                            </svg>


                            @if($notifNoLeidas > 0)

                                <span class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold rounded-full w-4 h-4 flex items-center justify-center">
                                    {{ $notifNoLeidas > 9 ? '9+' : $notifNoLeidas }}
                                </span>

                            @endif

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
                                        {{ Auth::user()->rol }}
                                    </p>

                                </div>


                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"/>

                                </svg>

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
                                    class="block px-4 py-2 text-sm hover:bg-white/10">

                                    👤 Mi Perfil

                                </a>


                                <!-- NOTIFICACIONES -->

                                <a
                                    href="{{ route('notificaciones.index') }}"
                                    class="block px-4 py-2 text-sm hover:bg-white/10">

                                    🔔 Notificaciones

                                </a>


                                <hr class="border-white/10 my-1">


                                <!-- LOGOUT -->

                                <form
                                    method="POST"
                                    action="{{ route('logout') }}">

                                    @csrf

                                    <button
                                        type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-red-200 hover:bg-white/10">

                                        🚪 Cerrar Sesión

                                    </button>

                                </form>

                            </div>

                        </div>

                    @else

                        <!-- LOGIN -->

                        <a
                            href="{{ route('login') }}"
                            class="text-white hover:bg-white/10 px-4 py-2 rounded-lg transition text-sm">

                            Iniciar Sesión

                        </a>


                        <!-- REGISTRO -->

                        <a
                            href="{{ route('register') }}"
                            class="btn-primary text-white px-4 py-2 rounded-lg text-sm font-medium">

                            Registrarse

                        </a>

                    @endauth

                </div>

            </div>

        </header>


        <!-- ALERTAS -->

        <div class="px-4 sm:px-6 pt-4">

            @if(session('success'))

                <div class="mb-4 glass text-white px-4 py-3 rounded-xl border-l-4 border-green-400 flex items-center gap-3">

                    <span>✅</span>

                    {{ session('success') }}

                </div>

            @endif


            @if(session('error'))

                <div class="mb-4 glass text-white px-4 py-3 rounded-xl border-l-4 border-red-400 flex items-center gap-3">

                    <span>⚠️</span>

                    {{ session('error') }}

                </div>

            @endif


            @if($errors->any())

                <div class="mb-4 glass text-white px-4 py-3 rounded-xl border-l-4 border-red-400">

                    <p class="font-bold mb-1">
                        ⚠️ Revisa los siguientes errores:
                    </p>


                    <ul class="list-disc list-inside text-sm">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif

        </div>


        <!-- CONTENIDO -->

        <main class="flex-1 p-4 sm:p-6">

            @yield('content')

        </main>


        <!-- FOOTER -->

        <footer class="px-6 py-4 text-center text-white/60 text-xs">

            &copy; {{ date('Y') }} TutorMatch — Todos los derechos reservados.

        </footer>

    </div>

</div>

</body>
</html>
