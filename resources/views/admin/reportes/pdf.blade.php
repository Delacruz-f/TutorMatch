<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('ui.report_title') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #333;
            padding: 20px;
        }
        .header {
            border-bottom: 3px solid #2563eb;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #2563eb;
            font-size: 24px;
            margin-bottom: 4px;
        }
        .header p {
            color: #666;
            font-size: 11px;
        }
        .periodo {
            background: #eff6ff;
            border-left: 4px solid #2563eb;
            padding: 10px;
            margin-bottom: 20px;
        }
        .periodo strong { color: #1e40af; }

        .cards {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .cards td {
            padding: 12px;
            border: 1px solid #e5e7eb;
            width: 25%;
            vertical-align: top;
        }
        .card-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .card-value {
            font-size: 18px;
            font-weight: bold;
            color: #111;
        }
        .card-value.green { color: #059669; }
        .card-value.blue { color: #2563eb; }
        .card-value.purple { color: #7c3aed; }
        .card-value.red { color: #dc2626; }

        h2 {
            font-size: 14px;
            color: #1e40af;
            margin: 20px 0 10px 0;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 4px;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.data th {
            background: #1e40af;
            color: white;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
        }
        table.data td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }
        table.data tr:nth-child(even) {
            background: #f9fafb;
        }
        .text-right { text-align: right; }
        .text-green { color: #059669; font-weight: bold; }
        .text-red { color: #dc2626; font-weight: bold; }

        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            font-size: 9px;
            color: #999;
            text-align: center;
        }
        .empty {
            color: #999;
            font-style: italic;
            padding: 10px;
        }
    </style>
</head>
<body>

    <!-- Encabezado -->
    <div class="header">
        <h1>TutorMatch</h1>
        <p>{{ __('ui.report_income_activity') }}</p>
    </div>

    <!-- Período -->
    <div class="periodo">
        <strong>{{ __('ui.report_period') }}</strong>
        {{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }}
        {{ __('ui.to_date') }}
        {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }}
        <br>
        <span style="font-size: 10px; color: #666;">
            {{ __('ui.generated_on') }} {{ now()->format('d/m/Y H:i') }}
        </span>
    </div>

    <!-- Resumen General -->
    <h2>{{ __('ui.general_summary') }}</h2>
    <table class="cards">
        <tr>
            <td>
                <div class="card-label">{{ __('ui.total_income') }}</div>
                <div class="card-value green">S/ {{ number_format($totalIngresos, 2) }}</div>
            </td>
            <td>
                <div class="card-label">{{ __('ui.class_commissions') }}</div>
                <div class="card-value blue">S/ {{ number_format($comisiones, 2) }}</div>
            </td>
            <td>
                <div class="card-label">{{ __('ui.subscription_income') }}</div>
                <div class="card-value purple">S/ {{ number_format($ingresosSuscripciones, 2) }}</div>
            </td>
            <td>
                <div class="card-label">{{ __('ui.completed_classes') }}</div>
                <div class="card-value">{{ $clasesCompletadas }}</div>
            </td>
        </tr>
    </table>

    <table class="cards">
        <tr>
            <td>
                <div class="card-label">{{ __('ui.new_users') }}</div>
                <div class="card-value blue">{{ $nuevosUsuarios }}</div>
            </td>
            <td>
                <div class="card-label">{{ __('ui.paid_withdrawals') }}</div>
                <div class="card-value red">S/ {{ number_format($retirosPagados, 2) }}</div>
            </td>
            <td colspan="2">
                <div class="card-label">{{ __('ui.net_platform_profit') }}</div>
                <div class="card-value green">
                    S/ {{ number_format($totalIngresos - $retirosPagados, 2) }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Top Tutores -->
    <h2>{{ __('ui.top_tutors_period') }}</h2>
    @if($topTutores->isEmpty())
        <p class="empty">{{ __('ui.no_data_period') }}</p>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('ui.tutor') }}</th>
                    <th>{{ __('ui.university_degree') }}</th>
                    <th class="text-right">{{ __('ui.generated_income') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topTutores as $i => $t)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><strong>{{ $t->tutor->user->nombre }} {{ $t->tutor->user->apellido }}</strong></td>
                        <td>{{ $t->tutor->carrera }} — {{ $t->tutor->universidad }}</td>
                        <td class="text-right text-green">S/ {{ number_format($t->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Detalle de Clases -->
    <h2>{{ __('ui.class_details_count', ['count' => $detalleClases->count()]) }}</h2>
    @if($detalleClases->isEmpty())
        <p class="empty">{{ __('ui.no_classes_period') }}</p>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th>{{ __('ui.date') }}</th>
                    <th>{{ __('ui.student') }}</th>
                    <th>{{ __('ui.tutor') }}</th>
                    <th>{{ __('ui.subject') }}</th>
                    <th class="text-right">{{ __('ui.total') }}</th>
                    <th class="text-right">{{ __('ui.commission') }}</th>
                    <th class="text-right">{{ __('ui.tutor_amount') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detalleClases as $c)
                    <tr>
                        <td>{{ $c->fecha->format('d/m/Y') }}</td>
                        <td>{{ $c->estudiante->nombre }} {{ $c->estudiante->apellido }}</td>
                        <td>{{ $c->tutor->user->nombre }} {{ $c->tutor->user->apellido }}</td>
                        <td>{{ $c->materia->nombre }}</td>
                        <td class="text-right">S/ {{ number_format($c->precio_total, 2) }}</td>
                        <td class="text-right text-green">S/ {{ number_format($c->comision_monto, 2) }}</td>
                        <td class="text-right">S/ {{ number_format($c->monto_tutor, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: #eff6ff; font-weight: bold;">
                    <td colspan="4" class="text-right">{{ __('ui.totals') }}</td>
                    <td class="text-right">S/ {{ number_format($detalleClases->sum('precio_total'), 2) }}</td>
                    <td class="text-right text-green">S/ {{ number_format($detalleClases->sum('comision_monto'), 2) }}</td>
                    <td class="text-right">S/ {{ number_format($detalleClases->sum('monto_tutor'), 2) }}</td>
                </tr>
            </tfoot>
        </table>
    @endif

    <!-- Footer -->
    <div class="footer">
        TutorMatch © {{ date('Y') }} — {{ __('ui.automatically_generated_report') }}
        <br>
        {{ __('ui.document_valid') }}
    </div>

</body>
</html>
