<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('ui.payment_receipt') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; color: #333; padding: 30px; }
        
        .header {
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header h1 { color: #4f46e5; font-size: 26px; }
        .header p { color: #666; font-size: 12px; }
        
        .badge {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }
        
        .info-box {
            background: #f3f4f6;
            border-left: 4px solid #4f46e5;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        h2 { font-size: 14px; color: #1e40af; margin: 20px 0 10px 0; border-bottom: 1px solid #e5e7eb; padding-bottom: 5px; }
        
        table.info { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.info td { padding: 8px 5px; border-bottom: 1px solid #e5e7eb; font-size: 12px; }
        table.info td:first-child { color: #6b7280; width: 40%; }
        table.info td:last-child { font-weight: bold; }
        
        .total-box {
            background: #4f46e5;
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        .total-box .label { font-size: 12px; opacity: 0.8; }
        .total-box .monto { font-size: 30px; font-weight: bold; margin: 5px 0; }
        
        table.desglose {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table.desglose th {
            background: #1e40af;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        table.desglose td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        .text-right { text-align: right; }
        .text-green { color: #059669; font-weight: bold; }
        
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #9ca3af;
            font-size: 10px;
        }
        
        .marca-agua {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 80px;
            color: rgba(79, 70, 229, 0.05);
            font-weight: bold;
            z-index: -1;
        }
    </style>
</head>
<body>

    <div class="marca-agua">TUTORMATCH</div>

    <!-- Encabezado -->
    <div class="header">
        <h1>TutorMatch</h1>
        <p>{{ __('ui.electronic_payment_receipt') }}</p>
        <p style="margin-top: 8px;">
            <span class="badge">{{ __('ui.payment_confirmed') }}</span>
        </p>
    </div>

    <!-- Datos del comprobante -->
    <div class="info-box">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%;">
                    <strong>{{ __('ui.receipt_number') }}</strong><br>
                    TM-{{ str_pad($pago->id, 6, '0', STR_PAD_LEFT) }}-{{ date('Y', strtotime($pago->pagado_en ?? $pago->created_at)) }}
                </td>
                <td style="width: 50%; text-align: right;">
                    <strong>{{ __('ui.issue_date') }}</strong><br>
                    {{ \Carbon\Carbon::parse($pago->pagado_en ?? $pago->created_at)->format('d/m/Y H:i') }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Datos del estudiante -->
    <h2>{{ __('ui.student_details') }}</h2>
    <table class="info">
        <tr>
            <td>{{ __('ui.full_name') }}</td>
            <td>{{ $clase->estudiante->nombre }} {{ $clase->estudiante->apellido }}</td>
        </tr>
        <tr>
            <td>{{ __('ui.email') }}</td>
            <td>{{ $clase->estudiante->email }}</td>
        </tr>
    </table>

    <!-- Datos del tutor -->
    <h2>{{ __('ui.tutor_details') }}</h2>
    <table class="info">
        <tr>
            <td>{{ __('ui.full_name') }}</td>
            <td>{{ $clase->tutor->user->nombre }} {{ $clase->tutor->user->apellido }}</td>
        </tr>
        <tr>
            <td>{{ __('ui.university_degree') }}</td>
            <td>{{ $clase->tutor->universidad }} — {{ $clase->tutor->carrera }}</td>
        </tr>
    </table>

    <!-- Detalles de la clase -->
    <h2>{{ __('ui.class_details') }}</h2>
    <table class="info">
        <tr>
            <td>{{ __('ui.subject') }}</td>
            <td>{{ $clase->materia->nombre }}</td>
        </tr>
        <tr>
            <td>{{ __('ui.date') }}</td>
            <td>{{ $clase->fecha->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td>{{ __('ui.schedule') }}</td>
            <td>{{ substr($clase->hora_inicio, 0, 5) }} - {{ substr($clase->hora_fin, 0, 5) }}</td>
        </tr>
        <tr>
            <td>{{ __('ui.duration') }}</td>
            <td>{{ $clase->duracion_min }} {{ __('ui.minutes') }}</td>
        </tr>
        <tr>
            <td>{{ __('ui.class_status_label') }}</td>
            <td>{{ __('ui.status_labels.' . $clase->estado) }}</td>
        </tr>
    </table>

    <!-- Desglose del pago -->
    <h2>{{ __('ui.payment_breakdown') }}</h2>
    <table class="desglose">
        <thead>
            <tr>
                <th>{{ __('ui.concept') }}</th>
                <th class="text-right">{{ __('ui.amount') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ __('ui.class_of') }} {{ $clase->materia->nombre }} ({{ $clase->duracion_min }} min)</td>
                <td class="text-right">S/ {{ number_format($clase->precio_total, 2) }}</td>
            </tr>
            <tr>
                <td style="font-size: 10px; color: #6b7280;">
                    {{ __('ui.includes_platform_commission') }} ({{ $clase->comision_porcentaje }}%)
                </td>
                <td class="text-right" style="font-size: 10px; color: #6b7280;">
                    S/ {{ number_format($clase->comision_monto, 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Total -->
    <div class="total-box">
        <div class="label">{{ __('ui.total_paid') }}</div>
        <div class="monto">S/ {{ number_format($clase->precio_total, 2) }}</div>
    </div>

    <!-- Datos del pago -->
    <h2>{{ __('ui.payment_details') }}</h2>
    <table class="info">
        <tr>
            <td>{{ __('ui.payment_method_label') }}</td>
            <td style="text-transform: uppercase;">{{ $pago->metodo }}</td>
        </tr>
        <tr>
            <td>{{ __('ui.operation_reference') }}</td>
            <td>{{ $pago->referencia }}</td>
        </tr>
        <tr>
            <td>{{ __('ui.validation_date') }}</td>
            <td>{{ \Carbon\Carbon::parse($pago->pagado_en)->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p><strong>TutorMatch</strong> — {{ __('ui.tutoring_platform') }}</p>
        <p>{{ __('ui.receipt_valid_without_signature') }}</p>
        <p>{{ __('ui.generated_on') }} {{ now()->format('d/m/Y H:i') }}</p>
    </div>

</body>
</html>