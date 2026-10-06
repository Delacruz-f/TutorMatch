<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; padding: 20px; margin: 0; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; }
        .header { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .body { padding: 30px; }
        .stars { text-align: center; font-size: 40px; margin: 20px 0; }
        .btn { display: inline-block; background: #6366f1; color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; font-weight: bold; }
        .footer { background: #1f2937; color: #9ca3af; padding: 20px; text-align: center; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏁 ¡Clase completada!</h1>
        </div>
        <div class="body">
            <p>Hola {{ $clase->estudiante->nombre }},</p>
            <p>Tu clase de <strong>{{ $clase->materia->nombre }}</strong> con <strong>{{ $clase->tutor->user->nombre }}</strong> ha sido completada.</p>

            <div class="stars">⭐⭐⭐⭐⭐</div>

            <p style="text-align: center;">¿Cómo calificarías tu experiencia? Tu opinión ayuda a otros estudiantes.</p>

            <p style="text-align: center; margin-top: 20px;">
                <a href="{{ route('resenas.create', $clase->id) }}" class="btn">Dejar reseña</a>
            </p>
        </div>
        <div class="footer">
            TutorMatch © {{ date('Y') }}
        </div>
    </div>
</body>
</html>