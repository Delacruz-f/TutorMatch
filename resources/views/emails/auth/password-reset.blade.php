@extends('emails.layout')

@section('email_title', __('ui.reset_password_title'))
@section('heading', __('ui.reset_password_heading'))

@section('content')
    <p style="margin:0 0 16px;font-size:15px;line-height:1.7;">{{ __('ui.hello_user', ['name' => $nombre]) }}</p>
    <p style="margin:0 0 24px;font-size:15px;line-height:1.7;color:#4b5563;">
        {{ __('ui.password_reset_message') }}
    </p>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:0 auto 24px;">
        <tr>
            <td align="center" style="border-radius:10px;background-color:#4f46e5;">
                <a href="{{ $url }}" style="display:inline-block;padding:14px 26px;border-radius:10px;color:#ffffff;text-decoration:none;font-size:15px;font-weight:bold;">{{ __('ui.create_new_password') }}</a>
            </td>
        </tr>
    </table>
    <p style="margin:0 0 12px;font-size:13px;line-height:1.7;color:#6b7280;">
        {{ __('ui.password_reset_expiry', ['minutes' => $minutos]) }}
    </p>
    <p style="margin:0 0 12px;font-size:13px;line-height:1.7;color:#6b7280;">
        {{ __('ui.email_link_help') }}
    </p>
    <p style="margin:0 0 24px;font-size:12px;line-height:1.6;word-break:break-all;">
        <a href="{{ $url }}" style="color:#4f46e5;">{{ $url }}</a>
    </p>
    <div style="padding:14px 16px;border-left:4px solid #818cf8;background-color:#eef2ff;border-radius:6px;color:#4338ca;font-size:13px;line-height:1.6;">
        {{ __('ui.ignore_unrequested_password_reset') }}
    </div>
@endsection
