@extends('emails.layout')

@section('email_title', 'Restablece tu contraseña - TutorMatch')
@section('heading', 'Restablece tu contraseña')

@section('content')
    <p style="margin:0 0 16px;font-size:15px;line-height:1.7;">Hola {{ $nombre }},</p>
    <p style="margin:0 0 24px;font-size:15px;line-height:1.7;color:#4b5563;">
        Recibimos una solicitud para cambiar la contraseña de tu cuenta. Usa el botón para elegir una nueva.
    </p>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:0 auto 24px;">
        <tr>
            <td align="center" style="border-radius:10px;background-color:#4f46e5;">
                <a href="{{ $url }}" style="display:inline-block;padding:14px 26px;border-radius:10px;color:#ffffff;text-decoration:none;font-size:15px;font-weight:bold;">Crear nueva contraseña</a>
            </td>
        </tr>
    </table>
    <p style="margin:0 0 12px;font-size:13px;line-height:1.7;color:#6b7280;">
        Este enlace vence en {{ $minutos }} minutos y solo se puede usar una vez.
    </p>
    <p style="margin:0 0 12px;font-size:13px;line-height:1.7;color:#6b7280;">
        Si el botón no funciona, copia y pega este enlace en tu navegador:
    </p>
    <p style="margin:0 0 24px;font-size:12px;line-height:1.6;word-break:break-all;">
        <a href="{{ $url }}" style="color:#4f46e5;">{{ $url }}</a>
    </p>
    <div style="padding:14px 16px;border-left:4px solid #818cf8;background-color:#eef2ff;border-radius:6px;color:#4338ca;font-size:13px;line-height:1.6;">
        Si no solicitaste este cambio, puedes ignorar este correo. Tu contraseña seguirá siendo la misma.
    </div>
@endsection
