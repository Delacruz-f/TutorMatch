<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TutorMatch - Iniciar Sesión</title>
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
            0%, 100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .input-glass {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
        }

        .input-glass::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .input-glass:focus {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.6);
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.15);
        }

        .btn-glass {
            background: rgba(255, 255, 255, 0.95);
            color: #4f46e5;
            transition: all 0.3s ease;
        }

        .btn-glass:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.4);
        }

        /* Burbujas decorativas */
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
    <link rel="stylesheet" href="{{ asset('theme.css') }}">
</head>

<body class="flex items-center justify-center p-4 overflow-hidden">

    <!-- Burbujas decorativas -->
    <div class="bubble w-64 h-64 top-10 left-10" style="animation-delay: 0s;"></div>
    <div class="bubble w-40 h-40 bottom-20 right-20" style="animation-delay: 2s;"></div>
    <div class="bubble w-24 h-24 top-1/2 right-1/4" style="animation-delay: 4s;"></div>

    <div class="glass-card rounded-3xl p-8 sm:p-10 w-full max-w-md relative z-10 text-white">

        <!-- Logo / Icono -->
        <div class="flex flex-col items-center mb-8">

            <div class="w-16 h-16 rounded-2xl bg-white/25 backdrop-blur flex items-center justify-center mb-4 border border-white/30 overflow-hidden">
                <img src="{{ asset('img/TUTORMATCH1.png') }}" alt="TutorMatch" class="w-full h-full object-contain">
            </div>

            <h1 class="text-3xl font-bold">
                TutorMatch
            </h1>

            <p class="text-white/70 text-sm mt-1">
                Bienvenido de vuelta
            </p>
        </div>

        <!-- Errores -->
        @if ($errors->any())
            <div class="bg-red-500/30 border border-red-300/40 backdrop-blur rounded-xl px-4 py-3 mb-4 text-sm">

                @foreach ($errors->all() as $error)
                    <p>⚠️ {{ $error }}</p>
                @endforeach

            </div>
        @endif

        <!-- Mensaje de éxito -->
        @if (session('success'))
            <div class="bg-green-500/30 border border-green-300/40 backdrop-blur rounded-xl px-4 py-3 mb-4 text-sm">
                <p>✅ {{ session('success') }}</p>
            </div>
        @endif
        <a href="{{ route('google.redirect') }}"
   class="flex items-center justify-center gap-3 w-full bg-white text-gray-800 py-3 rounded-xl font-medium hover:bg-gray-100 transition mb-4">
    <svg class="w-5 h-5" viewBox="0 0 24 24">
        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
    </svg>
    Continuar con Google
</a>

<div class="relative my-6">
    <div class="absolute inset-0 flex items-center">
        <div class="w-full border-t border-white/20"></div>
    </div>
    <div class="relative flex justify-center text-xs">
        <span class="px-3 bg-transparent text-white/60">o continúa con tu correo</span>
    </div>
</div>

        <!-- Formulario -->
        <form method="POST" action="{{ route('login') }}" class="space-y-5">

            @csrf

            <!-- Correo -->
            <div>
                <label class="block text-sm font-medium mb-2">
                    Correo electrónico
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    placeholder="tu@email.com"
                    class="input-glass w-full px-4 py-3 rounded-xl transition-all duration-200"
                >
            </div>

            <!-- Contraseña -->
            <div>
                <label class="block text-sm font-medium mb-2">
                    Contraseña
                </label>

                <input
                    type="password"
                    name="password"
                    required
                    placeholder="••••••••"
                    class="input-glass w-full px-4 py-3 rounded-xl transition-all duration-200"
                >
            </div>

            <!-- Recordarme / Recuperar contraseña -->
            <div class="flex items-center justify-between text-sm">

                <label class="flex items-center gap-2 cursor-pointer">

                    <input
                        type="checkbox"
                        name="remember"
                        class="rounded bg-white/20 border-white/40"
                    >

                    <span class="text-white/80">
                        Recordarme
                    </span>

                </label>

                <a
                    href="{{ route('password.request') }}"
                    class="text-white/80 hover:text-white hover:underline"
                >
                    ¿Olvidaste tu contraseña?
                </a>

            </div>

            <!-- Botón -->
            <button
                type="submit"
                class="btn-glass w-full py-3 rounded-xl font-bold text-base"
            >
                Iniciar Sesión
            </button>

        </form>

        <!-- Registro -->
        <p class="text-center text-sm mt-6 text-white/80">

            ¿No tienes cuenta?

            <a
                href="{{ route('register') }}"
                class="font-bold text-white hover:underline ml-1"
            >
                Regístrate
            </a>

        </p>

        <!-- Credenciales demo -->
        <div class="mt-6 pt-4 border-t border-white/20 text-xs text-white/70">

            <p class="font-bold mb-1">
                🔑 Credenciales de prueba:
            </p>

            <p>
                Admin: admin@tutormatch.test
            </p>

            <p>
                Tutor: tutor1@tutormatch.test
            </p>

            <p>
                Estudiante: estudiante@tutormatch.test
            </p>

            <p class="mt-1">
                Contraseña:
                <strong>Admin12345</strong>
            </p>

        </div>

    </div>

</body>
</html>
