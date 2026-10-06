<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elige tu rol - TutorMatch</title>
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
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
        .rol-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .rol-card:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(255, 255, 255, 0.3);
        }
        .rol-card.selected {
            background: rgba(255, 255, 255, 0.35);
            border-color: white;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">

    <div class="glass-card rounded-3xl p-8 sm:p-10 w-full max-w-2xl text-white" x-data="{ rolSeleccionado: '' }">
        
        <!-- Header -->
        <div class="text-center mb-8">
            @if($datos['foto_url'])
                <img src="{{ $datos['foto_url'] }}" class="w-20 h-20 rounded-full mx-auto mb-3 border-4 border-white/40">
            @endif
            <h1 class="text-3xl font-bold mb-2">¡Bienvenido, {{ $datos['nombre'] }}!</h1>
            <p class="text-white/80">{{ $datos['email'] }}</p>
            <p class="text-white/70 text-sm mt-2">Antes de continuar, cuéntanos cómo quieres usar TutorMatch</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('google.registrar') }}">
            @csrf
            <input type="hidden" name="rol" x-model="rolSeleccionado">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                
                <!-- Estudiante -->
                <div @click="rolSeleccionado = 'estudiante'"
                     :class="rolSeleccionado === 'estudiante' ? 'rol-card selected rounded-2xl p-6 text-center' : 'rol-card rounded-2xl p-6 text-center'">
                    <div class="text-6xl mb-3">👨‍🎓</div>
                    <h3 class="text-xl font-bold mb-2">Soy Estudiante</h3>
                    <p class="text-sm text-white/80 leading-relaxed">
                        Quiero buscar tutores y reservar clases particulares para reforzar mis estudios.
                    </p>
                    <div class="mt-4 text-xs text-white/70">
                        ✅ Buscar tutores<br>
                        ✅ Reservar clases<br>
                        ✅ Calificar y reseñar
                    </div>
                </div>

                <!-- Tutor -->
                <div @click="rolSeleccionado = 'tutor'"
                     :class="rolSeleccionado === 'tutor' ? 'rol-card selected rounded-2xl p-6 text-center' : 'rol-card rounded-2xl p-6 text-center'">
                    <div class="text-6xl mb-3">👨‍🏫</div>
                    <h3 class="text-xl font-bold mb-2">Soy Tutor</h3>
                    <p class="text-sm text-white/80 leading-relaxed">
                        Quiero ofrecer clases particulares y generar ingresos dando asesorías.
                    </p>
                    <div class="mt-4 text-xs text-white/70">
                        ✅ Subir documentos<br>
                        ✅ Configurar horarios<br>
                        ✅ Recibir pagos
                    </div>
                </div>
            </div>

            <button type="submit"
                    :disabled="!rolSeleccionado"
                    :class="rolSeleccionado ? 'bg-white text-brand-600 hover:bg-white/90' : 'bg-white/20 cursor-not-allowed'"
                    class="w-full py-3 rounded-xl font-bold text-base transition">
                <span x-show="!rolSeleccionado">Elige un rol para continuar</span>
                <span x-show="rolSeleccionado" x-text="rolSeleccionado === 'estudiante' ? '🎓 Continuar como Estudiante' : '👨‍🏫 Continuar como Tutor'"></span>
            </button>
        </form>

        <p class="text-center text-xs text-white/60 mt-6">
            Al continuar, aceptas nuestros términos y condiciones.
        </p>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>