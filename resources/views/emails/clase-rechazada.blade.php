@extends('emails.layout')

@section('email_title', 'Actualización de tu solicitud - TutorMatch')
@section('heading', 'Actualización de tu solicitud')

@section('content')
    <p style="margin:0 0 16px;font-size:15px;line-height:1.7;">Hola {{ $clase->estudiante->nombre }},</p>
    <p style="margin:0 0 20px;font-size:15px;line-height:1.7;color:#4b5563;">
        Lamentamos informarte que <strong>{{ $clase->tutor->user->nombre }} {{ $clase->tutor->user->apellido }}</strong> no podrá atender la clase que solicitaste.
    </p>
    <div style="padding:18px 20px;margin-bottom:22px;border:1px solid #e5e7eb;border-radius:10px;background-color:#f9fafb;font-size:14px;line-height:1.9;">
        <strong>Materia:</strong> {{ $clase->materia->nombre }}<br>
        <strong>Fecha solicitada:</strong> {{ $clase->fecha->format('d/m/Y') }}<br>
        <strong>Nota:</strong> {{ $clase->notas ?? 'Sin detalles adicionales.' }}
    </div>
    <p style="margin:0 0 22px;font-size:14px;line-height:1.7;color:#4b5563;">
        Puedes buscar otro tutor y enviar una nueva solicitud.
    </p>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:0 auto;">
        <tr>
            <td align="center" style="border-radius:10px;background-color:#4f46e5;">
                <a href="{{ route('busqueda.index') }}" style="display:inline-block;padding:13px 24px;border-radius:10px;color:#ffffff;text-decoration:none;font-size:14px;font-weight:bold;">Buscar otro tutor</a>
            </td>
        </tr>
    </table>
@endsection
