<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('email_title', 'TutorMatch')</title>
</head>
<body style="margin:0;padding:0;background-color:#f3f4f6;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color:#f3f4f6;padding:32px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:600px;background-color:#ffffff;border-radius:16px;overflow:hidden;">
                    <tr>
                        <td align="center" style="background-color:#4f46e5;background-image:linear-gradient(135deg,#4f46e5,#7c3aed);padding:30px 24px;color:#ffffff;">
                            <div style="font-size:13px;font-weight:bold;letter-spacing:2px;text-transform:uppercase;">TutorMatch</div>
                            <div style="font-size:13px;margin-top:7px;color:#e0e7ff;">Aprende y enseña con confianza</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:36px 32px 28px;">
                            <h1 style="margin:0 0 20px;font-size:24px;line-height:1.3;color:#312e81;">@yield('heading')</h1>
                            @yield('content')
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="background-color:#f9fafb;border-top:1px solid #e5e7eb;padding:20px 24px;color:#6b7280;font-size:12px;line-height:1.6;">
                            TutorMatch &copy; {{ date('Y') }}<br>
                            Plataforma de tutorías en línea
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
