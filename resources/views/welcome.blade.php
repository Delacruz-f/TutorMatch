<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TutorMatch - Encuentra tu Tutor Ideal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(-45deg, #667eea, #764ba2, #6366f1, #8b5cf6);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            min-height: 100vh;
        }
        @keyframes gradient {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .glass {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            transition: all 0.3s ease;
        }
        .glass-card:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-5px);
        }
        .btn-white {
            background: white;
            color: #4f46e5;
            transition: all 0.3s ease;
        }
        .btn-white:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.4);
        }
        .btn-yellow {
            background: #fbbf24;
            color: #1f2937;
            transition: all 0.3s ease;
        }
        .btn-yellow:hover {
            background: #f59e0b;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(251, 191, 36, 0.4);
        }
        .bubble {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            animation: float 8s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-30px); }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('theme.css') }}">
</head>
<body class="text-white overflow-x-hidden">

    <div class="bubble w-96 h-96 top-20 left-10"></div>
    <div class="bubble w-64 h-64 top-1/3 right-10" style="animation-delay: 3s;"></div>
    <div class="bubble w-48 h-48 bottom-20 left-1/3" style="animation-delay: 5s;"></div>

    <!-- NAVBAR -->
    <nav class="glass sticky top-0 z-50 px-4 sm:px-8">
        <div class="max-w-7xl mx-auto flex justify-between items-center h-16">
            <a href="/" class="text-2xl font-bold">TutorMatch</a>
            <div class="flex items-center gap-3">
                @auth
                    <span class="hidden sm:inline text-white/90">Hola, {{ Auth::user()->nombre }}</span>
                    @php
                        $rutaDash = Auth::user()->rol === 'administrador' ? 'admin.dashboard' :
                                   (Auth::user()->rol === 'tutor' ? 'tutor.dashboard' : 'estudiante.dashboard');
                    @endphp
                    <a href="{{ route($rutaDash) }}" class="btn-white px-5 py-2 rounded-xl font-bold text-sm">Mi Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-white/80 hover:text-white text-sm px-3">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-white/90 hover:text-white px-4 py-2 font-medium text-sm">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="btn-white px-5 py-2 rounded-xl font-bold text-sm">Registrarse</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="relative max-w-7xl mx-auto px-4 sm:px-8 py-20 text-center">
        <div class="glass rounded-3xl p-10 sm:p-16 relative z-10">
            <span class="inline-block bg-white/20 px-4 py-1.5 rounded-full text-sm mb-6">
                🎓 La plataforma #1 de tutorías en Perú
            </span>
            <h1 class="text-4xl sm:text-6xl font-extrabold mb-6 leading-tight">
                Encuentra al Tutor<br>
                <span class="text-yellow-300">Perfecto</span> para ti
            </h1>
            <p class="text-lg sm:text-xl text-white/80 mb-8 max-w-2xl mx-auto">
                Clases particulares con universitarios destacados. Matemáticas, Inglés, Física, y mucho más.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="btn-white px-8 py-4 rounded-2xl font-bold text-lg">
                    🎓 Comenzar como Estudiante
                </a>
                <a href="{{ route('register') }}" class="btn-yellow px-8 py-4 rounded-2xl font-bold text-lg">
                    👨‍🏫 Ser Tutor
                </a>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="max-w-7xl mx-auto px-4 sm:px-8 py-16 relative z-10">
        <h2 class="text-3xl sm:text-4xl font-bold text-center mb-12">¿Por qué elegir TutorMatch?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="glass-card rounded-3xl p-8 text-center">
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-xl font-bold mb-3">Búsqueda Inteligente</h3>
                <p class="text-white/80">Encuentra tutores por materia, precio y calificación en segundos.</p>
            </div>
            <div class="glass-card rounded-3xl p-8 text-center">
                <div class="text-6xl mb-4">📹</div>
                <h3 class="text-xl font-bold mb-3">Clases Virtuales</h3>
                <p class="text-white/80">Videollamadas en vivo con Jitsi, sin descargar nada.</p>
            </div>
            <div class="glass-card rounded-3xl p-8 text-center">
                <div class="text-6xl mb-4">⭐</div>
                <h3 class="text-xl font-bold mb-3">Tutores Verificados</h3>
                <p class="text-white/80">Todos nuestros tutores pasan por un riguroso proceso de verificación.</p>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="glass mt-12 py-6 text-center text-white/70 text-sm">
        &copy; {{ date('Y') }} TutorMatch — Todos los derechos reservados.
    </footer>

</body>
</html>
