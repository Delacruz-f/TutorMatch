<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; padding: 20px; margin: 0; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; }
        .header { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .body { padding: 30px; }
        .info-box { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .info-box p { margin: 5px 0; }
        .btn { display: inline-block; background: #f59e0b; color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; font-weight: bold; margin-top: 20px; }
        .footer { background: #1f2937; color: #9ca3af; padding: 20px; text-align: center; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⏰ ¡Tu clase empieza pronto!</h1>
        </div>
        <div class="body">
            <p>Hola {{ $clase->estudiante->nombre }},</p>
            <p>Recuerda que tu clase comienza en <strong>30 minutos</strong>.</p>

            <div class="info-box">
                <p><strong>📚 Materia:</strong> {{ $clase->materia->nombre }}</p>
                <p><strong>👨‍🏫 Tutor:</strong> {{ $clase->tutor->user->nombre }} {{ $clase->tutor->user->apellido }}</p>
                <p><strong>🕐 Hora:</strong> {{ substr($clase->hora_inicio, 0, 5) }}</p>
            </div>

            <p>Puedes entrar a la sala de videollamada desde 5 minutos antes.</p>

            <p style="text-align: center;">
                <a href="{{ route('clases.sala', $clase->id) }}" class="btn">Entrar a la sala</a>
            </p>
        </div>
        <div class="footer">TutorMatch © {{ date('Y') }}</div>
    </div>
</body>
</html>