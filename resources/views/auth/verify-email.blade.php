<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verifica tu correo - TutorMatch</title>
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
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
    </style>
    <link rel="stylesheet" href="{{ asset('theme.css') }}">
</head>
<body class="flex items-center justify-center p-4">
    <div class="glass-card rounded-3xl p-10 w-full max-w-md text-white text-center">
        <div class="text-6xl mb-4">📧</div>
        <h1 class="text-2xl font-bold mb-3">Verifica tu correo</h1>
        <p class="text-white/80 mb-6 text-sm">
            Te enviamos un enlace de verificación a <strong>{{ Auth::user()->email }}</strong>.
            Revisa tu bandeja de entrada y haz clic en el enlace.
        </p>

        @if(session('success'))
            <div class="bg-green-500/30 border border-green-300/40 rounded-xl px-4 py-3 mb-4 text-sm">
                ✅ {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="bg-white text-brand-600 w-full py-3 rounded-xl font-bold hover:bg-white/90 transition">
                Reenviar correo
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="text-white/70 text-sm hover:text-white">
                Cerrar sesión
            </button>
        </form>
    </div>
</body>
</html>
