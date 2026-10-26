<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña - TutorMatch</title>
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
        .input-glass {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
        }
        .input-glass::placeholder { color: rgba(255, 255, 255, 0.6); }
        .input-glass:focus {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.6);
            outline: none;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('theme.css') }}">
</head>
<body class="flex items-center justify-center p-4">
    <div class="glass-card rounded-3xl p-10 w-full max-w-md text-white">
        <div class="text-center mb-6">
            <div class="text-5xl mb-3">🔑</div>
            <h1 class="text-2xl font-bold">Recuperar contraseña</h1>
            <p class="text-white/70 text-sm mt-2">Te enviaremos un enlace para restablecerla</p>
        </div>

        @if(session('success'))
            <div class="bg-green-500/30 border border-green-300/40 rounded-xl px-4 py-3 mb-4 text-sm">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-500/30 border border-red-300/40 rounded-xl px-4 py-3 mb-4 text-sm">
                @foreach($errors->all() as $e) <p>⚠️ {{ $e }}</p> @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-2">Correo electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       placeholder="tu@email.com"
                       class="input-glass w-full px-4 py-3 rounded-xl transition-all">
            </div>
            <button type="submit" class="bg-white text-brand-600 w-full py-3 rounded-xl font-bold hover:bg-white/90 transition">
                Enviar enlace
            </button>
        </form>

        <p class="text-center text-sm mt-6 text-white/80">
            <a href="{{ route('login') }}" class="hover:underline">← Volver al login</a>
        </p>
    </div>
</body>
</html>