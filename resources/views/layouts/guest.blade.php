<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.seo')

    <title>@yield('title', 'TutorMatch')</title>

    {{-- Alpine.js (compartido: está en app.blade.php y lo usa google-elegir-rol) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Estilos base comunes a todas las pantallas "guest" --}}
    <style>
        /*
            Base de las pantallas "guest".
            Tipografia, fondo y .glass-card se definen en theme.css;
            aqui solo lo exclusivo de estas pantallas.
        */
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            min-height: 100vh;
        }

        /* Burbujas decorativas (solo donde se usan) */
        .bubble {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }
    </style>

    @stack('styles')
</head>

<body class="@yield('bodyClass')">

    @unless (View::hasSection('languageSwitcherInNavbar'))
        <div class="fixed right-4 top-4 z-50 rounded-xl border border-white/15 bg-slate-950/70 p-1 shadow-lg backdrop-blur">
            @include('partials.language-switcher')
        </div>
    @endunless

    @yield('content')

    @stack('scripts')
</body>
</html>
