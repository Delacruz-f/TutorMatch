@extends('emails.layout')

@section('email_title', __('ui.class_reminder_email_title'))
@section('heading', __('ui.class_starts_soon'))

@section('content')
    <p style="margin:0 0 16px;font-size:15px;line-height:1.7;">{{ __('ui.hello_user', ['name' => $clase->estudiante->nombre]) }}</p>
    <p style="margin:0 0 20px;font-size:15px;line-height:1.7;color:#4b5563;">
        {{ __('ui.class_reminder_intro') }}
    </p>
    <div style="padding:18px 20px;margin-bottom:22px;border:1px solid #e5e7eb;border-radius:10px;background-color:#f9fafb;font-size:14px;line-height:1.9;">
        <strong>{{ __('ui.email_class_label') }}</strong> {{ $clase->materia->nombre }}<br>
        <strong>{{ __('ui.email_tutor_label') }}</strong> {{ $clase->tutor->user->nombre }} {{ $clase->tutor->user->apellido }}<br>
        <strong>{{ __('ui.time_label') }}</strong> {{ substr($clase->hora_inicio, 0, 5) }}
    </div>
    <p style="margin:0 0 22px;font-size:14px;line-height:1.7;color:#4b5563;">
        {{ __('ui.classroom_available_soon') }}
    </p>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:0 auto;">
        <tr>
            <td align="center" style="border-radius:10px;background-color:#4f46e5;">
                <a href="{{ route('clases.sala', $clase->id) }}" style="display:inline-block;padding:13px 24px;border-radius:10px;color:#ffffff;text-decoration:none;font-size:14px;font-weight:bold;">{{ __('ui.enter_classroom') }}</a>
            </td>
        </tr>
    </table>
@endsection
