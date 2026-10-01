<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TutorMatch - @yield('title', 'Inicio')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="/" class="text-2xl font-bold text-blue-600">TutorMatch</a>

                <div class="flex items-center space-x-3">
                    @auth
                        <span class="text-gray-700 hidden sm:inline">Hola, {{ Auth::user()->nombre }}</span>

                        @if(Auth::user()->rol === 'administrador')
                            <a href="{{ route('admin.dashboard') }}" class="text-purple-600 hover:text-purple-800 font-medium">Dashboard</a>
                        @elseif(Auth::user()->rol === 'tutor')
                            <a href="{{ route('tutor.dashboard') }}" class="text-green-600 hover:text-green-800 font-medium">Dashboard</a>
                        @else
                            <a href="{{ route('estudiante.dashboard') }}" class="text-blue-600 hover:text-blue-800 font-medium">Dashboard</a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                Cerrar Sesión
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Registrarse</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
