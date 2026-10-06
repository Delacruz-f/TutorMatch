<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte TutorMatch</title>
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
        <p>Reporte de Ingresos y Actividad de la Plataforma</p>
    </div>

    <!-- Período -->
    <div class="periodo">
        <strong>Período del reporte:</strong>
        {{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }}
        al
        {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }}
        <br>
        <span style="font-size: 10px; color: #666;">
            Generado el {{ now()->format('d/m/Y H:i') }}
        </span>
    </div>

    <!-- Resumen General -->
    <h2>Resumen General</h2>
    <table class="cards">
        <tr>
            <td>
                <div class="card-label">Ingresos Totales</div>
                <div class="card-value green">S/ {{ number_format($totalIngresos, 2) }}</div>
            </td>
            <td>
                <div class="card-label">Comisiones por Clases</div>
                <div class="card-value blue">S/ {{ number_format($comisiones, 2) }}</div>
            </td>
            <td>
                <div class="card-label">Suscripciones</div>
                <div class="card-value purple">S/ {{ number_format($ingresosSuscripciones, 2) }}</div>
            </td>
            <td>
                <div class="card-label">Clases Completadas</div>
                <div class="card-value">{{ $clasesCompletadas }}</div>
            </td>
        </tr>
    </table>

    <table class="cards">
        <tr>
            <td>
                <div class="card-label">Nuevos Usuarios</div>
                <div class="card-value blue">{{ $nuevosUsuarios }}</div>
            </td>
            <td>
                <div class="card-label">Retiros Pagados</div>
                <div class="card-value red">S/ {{ number_format($retirosPagados, 2) }}</div>
            </td>
            <td colspan="2">
                <div class="card-label">Ganancia Neta de la Plataforma</div>
                <div class="card-value green">
                    S/ {{ number_format($totalIngresos - $retirosPagados, 2) }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Top Tutores -->
    <h2>Top Tutores del Período</h2>
    @if($topTutores->isEmpty())
        <p class="empty">Sin datos en este período.</p>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tutor</th>
                    <th>Universidad / Carrera</th>
                    <th class="text-right">Ingresos Generados</th>
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
    <h2>Detalle de Clases Completadas ({{ $detalleClases->count() }})</h2>
    @if($detalleClases->isEmpty())
        <p class="empty">No hay clases completadas en este período.</p>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Estudiante</th>
                    <th>Tutor</th>
                    <th>Materia</th>
                    <th class="text-right">Total</th>
                    <th class="text-right">Comisión</th>
                    <th class="text-right">Tutor</th>
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
                    <td colspan="4" class="text-right">TOTALES:</td>
                    <td class="text-right">S/ {{ number_format($detalleClases->sum('precio_total'), 2) }}</td>
                    <td class="text-right text-green">S/ {{ number_format($detalleClases->sum('comision_monto'), 2) }}</td>
                    <td class="text-right">S/ {{ number_format($detalleClases->sum('monto_tutor'), 2) }}</td>
                </tr>
            </tfoot>
        </table>
    @endif

    <!-- Footer -->
    <div class="footer">
        TutorMatch © {{ date('Y') }} — Reporte generado automáticamente por el sistema.
        <br>
        Documento válido sin firma.
    </div>

</body>
</html>
