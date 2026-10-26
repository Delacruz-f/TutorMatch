@extends('emails.layout')

@section('email_title', 'Recordatorio de clase - TutorMatch')
@section('heading', 'Tu clase comienza pronto')

@section('content')
    <p style="margin:0 0 16px;font-size:15px;line-height:1.7;">Hola {{ $clase->estudiante->nombre }},</p>
    <p style="margin:0 0 20px;font-size:15px;line-height:1.7;color:#4b5563;">
        Te recordamos que tu clase comienza en aproximadamente 30 minutos.
    </p>
    <div style="padding:18px 20px;margin-bottom:22px;border:1px solid #e5e7eb;border-radius:10px;background-color:#f9fafb;font-size:14px;line-height:1.9;">
        <strong>Materia:</strong> {{ $clase->materia->nombre }}<br>
        <strong>Tutor:</strong> {{ $clase->tutor->user->nombre }} {{ $clase->tutor->user->apellido }}<br>
        <strong>Hora:</strong> {{ substr($clase->hora_inicio, 0, 5) }}
    </div>
    <p style="margin:0 0 22px;font-size:14px;line-height:1.7;color:#4b5563;">
        La sala estará disponible cinco minutos antes de la hora programada.
    </p>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:0 auto;">
        <tr>
            <td align="center" style="border-radius:10px;background-color:#4f46e5;">
                <a href="{{ route('clases.sala', $clase->id) }}" style="display:inline-block;padding:13px 24px;border-radius:10px;color:#ffffff;text-decoration:none;font-size:14px;font-weight:bold;">Entrar a la sala</a>
            </td>
        </tr>
    </table>
@endsection
