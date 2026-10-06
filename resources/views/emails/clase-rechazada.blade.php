<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; padding: 20px; margin: 0; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; }
        .header { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .body { padding: 30px; }
        .info-box { background: #fef2f2; border-left: 4px solid #ef4444; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .footer { background: #1f2937; color: #9ca3af; padding: 20px; text-align: center; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>❌ Tu solicitud fue rechazada</h1>
        </div>
        <div class="body">
            <p>Hola {{ $clase->estudiante->nombre }},</p>
            <p>Lamentamos informarte que <strong>{{ $clase->tutor->user->nombre }} {{ $clase->tutor->user->apellido }}</strong> no podrá atender la clase que solicitaste.</p>

            <div class="info-box">
                <p><strong>📚 Materia:</strong> {{ $clase->materia->nombre }}</p>
                <p><strong>📅 Fecha solicitada:</strong> {{ $clase->fecha->format('d/m/Y') }}</p>
                <p><strong>📝 Motivo:</strong> {{ $clase->notas ?? 'No especificado' }}</p>
            </div>

            <p>Puedes buscar otro tutor disponible en la plataforma.</p>
            <p style="text-align:center; margin-top: 20px;">
                <a href="{{ route('busqueda.index') }}" style="background: #4f46e5; color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; font-weight: bold;">Buscar otro tutor</a>
            </p>
        </div>
        <div class="footer">
            TutorMatch © {{ date('Y') }}
        </div>
    </div>
</body>
</html>