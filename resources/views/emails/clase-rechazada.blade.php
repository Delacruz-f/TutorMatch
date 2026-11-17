@extends('emails.layout')

@section('email_title', __('ui.request_update_email_title'))
@section('heading', __('ui.request_update_email_heading'))

@section('content')
    <p style="margin:0 0 16px;font-size:15px;line-height:1.7;">{{ __('ui.hello_user', ['name' => $clase->estudiante->nombre]) }}</p>
    <p style="margin:0 0 20px;font-size:15px;line-height:1.7;color:#4b5563;">
        {{ __('ui.tutor_cannot_teach', ['tutor' => $clase->tutor->user->nombre . ' ' . $clase->tutor->user->apellido]) }}
    </p>
    <div style="padding:18px 20px;margin-bottom:22px;border:1px solid #e5e7eb;border-radius:10px;background-color:#f9fafb;font-size:14px;line-height:1.9;">
        <strong>{{ __('ui.email_class_label') }}</strong> {{ $clase->materia->nombre }}<br>
        <strong>{{ __('ui.requested_date') }}</strong> {{ $clase->fecha->format('d/m/Y') }}<br>
        <strong>{{ __('ui.note_label') }}</strong> {{ $clase->notas ?? __('ui.no_additional_details') }}
    </div>
    <p style="margin:0 0 22px;font-size:14px;line-height:1.7;color:#4b5563;">
        {{ __('ui.search_another_tutor') }}
    </p>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:0 auto;">
        <tr>
            <td align="center" style="border-radius:10px;background-color:#4f46e5;">
                <a href="{{ route('busqueda.index') }}" style="display:inline-block;padding:13px 24px;border-radius:10px;color:#ffffff;text-decoration:none;font-size:14px;font-weight:bold;">{{ __('ui.find_another_tutor') }}</a>
            </td>
        </tr>
    </table>
@endsection
