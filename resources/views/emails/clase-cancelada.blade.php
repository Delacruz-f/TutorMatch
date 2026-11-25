@extends('emails.layout')

@section('email_title', __('ui.class_cancelled_email_subject'))
@section('heading', __('ui.class_cancelled_email_heading'))

@section('content')
    @php
        $nombreDestinatario = $esEstudiante
            ? $clase->estudiante->nombre
            : $clase->tutor->user->nombre;
    @endphp

    <p style="margin:0 0 16px;font-size:15px;line-height:1.7;">
        {{ __('ui.hello_user', ['name' => $nombreDestinatario]) }}
    </p>

    <p style="margin:0 0 20px;font-size:15px;line-height:1.7;color:#4b5563;">
        @if($esEstudiante)
            {{ __('ui.class_cancelled_student_email_message', [
                'subject' => $clase->materia->nombre,
                'date' => $clase->fecha->format('d/m/Y'),
            ]) }}
        @else
            {{ __('ui.class_cancelled_tutor_email_message', [
                'student' => $clase->estudiante->nombre,
                'subject' => $clase->materia->nombre,
                'date' => $clase->fecha->format('d/m/Y'),
            ]) }}
        @endif
    </p>

    @if($reembolsoSolicitado)
        <p style="margin:0 0 20px;font-size:14px;line-height:1.7;color:#4b5563;">
            {{ __('ui.class_refund_email_notice', [
                'amount' => number_format((float) ($montoReembolso ?? $clase->precio_total), 2),
            ]) }}
        </p>
    @endif

    <div style="padding:18px 20px;margin-bottom:22px;border:1px solid #e5e7eb;border-radius:10px;background-color:#f9fafb;font-size:14px;line-height:1.9;">
        <strong>{{ __('ui.email_class_label') }}</strong> {{ $clase->materia->nombre }}<br>
        <strong>{{ __('ui.date') }}:</strong> {{ $clase->fecha->format('d/m/Y') }}<br>
        <strong>{{ __('ui.time_label') }}</strong> {{ substr($clase->hora_inicio, 0, 5) }} - {{ substr($clase->hora_fin, 0, 5) }}<br>
        <strong>{{ __('ui.total') }}:</strong> S/ {{ number_format((float) $clase->precio_total, 2) }}
    </div>
@endsection
