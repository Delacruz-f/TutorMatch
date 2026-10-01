<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TutorMatch - Encuentra tu Tutor Ideal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 font-sans antialiased">

    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="/" class="text-2xl font-bold text-blue-600">TutorMatch</a>

                <div class="flex items-center space-x-3">
                    @auth
                        {{-- Usuario logueado: mostrar dashboard según rol --}}
                        <span class="text-gray-700 hidden sm:inline">Hola, {{ Auth::user()->nombre }}</span>

                        @if(Auth::user()->rol === 'administrador')
                            <a href="{{ route('admin.dashboard') }}" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition">
                                Mi Dashboard
                            </a>
                        @elseif(Auth::user()->rol === 'tutor')
                            <a href="{{ route('tutor.dashboard') }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">
                                Mi Dashboard
                            </a>
                        @else
                            <a href="{{ route('estudiante.dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                                Mi Dashboard
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800 px-3 py-2">
                                Salir
                            </button>
                        </form>
                    @else
                        {{-- Usuario NO logueado: mostrar login y registro --}}
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 px-4 py-2 font-medium">
                            Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 transition font-medium">
                            Registrarse
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                Encuentra al Tutor Perfecto para ti
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-blue-100">
                Clases particulares con universitarios destacados. Matemáticas, Inglés, Física y más.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition shadow-lg">
                    Comenzar como Estudiante
                </a>
                <a href="{{ route('register') }}" class="bg-yellow-400 text-gray-900 px-8 py-4 rounded-lg font-bold text-lg hover:bg-yellow-300 transition shadow-lg">
                    Ser Tutor
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">¿Por qué elegir TutorMatch?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-lg shadow-md text-center hover:shadow-xl transition">
                <div class="text-5xl mb-4">🔍</div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Búsqueda Fácil</h3>
                <p class="text-gray-600">Encuentra tutores por materia, precio y calificación en segundos.</p>
            </div>
            <div class="bg-white p-8 rounded-lg shadow-md text-center hover:shadow-xl transition">
                <div class="text-5xl mb-4">📹</div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Clases Virtuales</h3>
                <p class="text-gray-600">Videollamadas en vivo con Jitsi, sin descargar nada.</p>
            </div>
            <div class="bg-white p-8 rounded-lg shadow-md text-center hover:shadow-xl transition">
                <div class="text-5xl mb-4">⭐</div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Tutores Verificados</h3>
                <p class="text-gray-600">Todos nuestros tutores pasan por un proceso de verificación.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} TutorMatch. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>
