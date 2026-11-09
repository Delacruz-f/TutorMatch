@extends('emails.layout')

@section('email_title', 'Confirma tu correo - TutorMatch')
@section('heading', 'Confirma tu correo electrónico')

@section('content')
    <p style="margin:0 0 16px;font-size:15px;line-height:1.7;">Hola {{ $nombre }},</p>
    <p style="margin:0 0 24px;font-size:15px;line-height:1.7;color:#4b5563;">
        Gracias por unirte a TutorMatch. Confirma tu correo para continuar usando tu cuenta.
    </p>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:0 auto 24px;">
        <tr>
            <td align="center" style="border-radius:10px;background-color:#8b5e3c;">
                <a href="{{ $url }}" style="display:inline-block;padding:14px 26px;border-radius:10px;color:#ffffff;text-decoration:none;font-size:15px;font-weight:bold;">Confirmar correo</a>
            </td>
        </tr>
    </table>
    <p style="margin:0 0 12px;font-size:13px;line-height:1.7;color:#6b7280;">
        Si el botón no funciona, copia y pega este enlace en tu navegador:
    </p>
    <p style="margin:0 0 24px;font-size:12px;line-height:1.6;word-break:break-all;">
        <a href="{{ $url }}" style="color:#8b5e3c;">{{ $url }}</a>
    </p>
    <div style="padding:14px 16px;border-left:4px solid #8b5e3c;background-color:#f5eee9;border-radius:6px;color:#70482d;font-size:13px;line-height:1.6;">
        Si no creaste una cuenta en TutorMatch, puedes ignorar este correo.
    </div>
@endsection
