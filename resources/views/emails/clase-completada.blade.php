@extends('emails.layout')

@section('email_title', __('ui.class_completed_email_title'))
@section('heading', __('ui.class_completed_email_heading'))

@section('content')
    <p style="margin:0 0 16px;font-size:15px;line-height:1.7;">{{ __('ui.hello_user', ['name' => $clase->estudiante->nombre]) }}</p>
    <p style="margin:0 0 24px;font-size:15px;line-height:1.7;color:#4b5563;">
        {{ __('ui.class_completed_message', ['subject' => $clase->materia->nombre, 'tutor' => $clase->tutor->user->nombre]) }}
    </p>
    <div style="padding:18px 20px;margin-bottom:24px;border:1px solid #e0e7ff;border-radius:10px;background-color:#eef2ff;color:#3730a3;font-size:14px;line-height:1.7;text-align:center;">
        {{ __('ui.review_prompt_email') }}
    </div>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:0 auto;">
        <tr>
            <td align="center" style="border-radius:10px;background-color:#4f46e5;">
                <a href="{{ route('resenas.create', $clase->id) }}" style="display:inline-block;padding:13px 24px;border-radius:10px;color:#ffffff;text-decoration:none;font-size:14px;font-weight:bold;">{{ __('ui.leave_a_review') }}</a>
            </td>
        </tr>
    </table>
@endsection
