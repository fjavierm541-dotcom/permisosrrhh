<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitudes por mes</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1f3a56, #2d4f73);
            min-height: 100vh;
            padding: 25px;
        }

        .container {
            max-width: 1120px;
            margin: auto;
            background: rgba(255,255,255,0.96);
            padding: 24px;
            border-radius: 18px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
        }

        .header {
            background: #2d4f73;
            color: white;
            padding: 18px;
            border-radius: 14px;
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 22px;
        }

        .header p {
            margin: 6px 0 0;
            font-size: 14px;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            margin-bottom: 18px;
            gap: 12px;
        }

        select {
            padding: 7px 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .btn {
            padding: 8px 14px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-primary {
            background: #1f3a56;
            color: white;
        }

        .btn-gold {
            background: #d4b06a;
            color: #1f3a56;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th {
            background: #2d4f73;
            color: white;
            padding: 8px;
        }

        td {
            padding: 7px 8px;
            border-bottom: 1px solid #ddd;
        }

        .text-left { text-align: left; }
        .text-center { text-align: center; }

        @media print {
    .no-print {
        display: none !important;
    }

    html, body {
        background: white !important;
        padding: 0 !important;
        margin: 0 !important;
        min-height: auto !important;
        height: auto !important;
    }

    .container {
        box-shadow: none !important;
        border-radius: 0 !important;
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    table {
        font-size: 10.5px;
        page-break-after: auto;
    }

    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }

    thead {
        display: table-header-group;
    }

    tfoot {
        display: table-footer-group;
    }

    th, td {
        padding: 5px;
    }
}

            @media print {
    @page {
        size: letter;
        margin: 12mm;
    }

    body {
        counter-reset: page;
    }

    .page-number::after {
        content: "Página " counter(page);
    }
}
@media print {
    .print-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 10px;
        color: #555;
    }
}
        }
    </style>
</head>

<body>

@php
    \Carbon\Carbon::setLocale('es');
    $nombreMes = \Carbon\Carbon::createFromDate($anio, $mes, 1)->translatedFormat('F');
@endphp

<div class="container">

    <div class="header">
        <h2>Solicitudes de Trabajo en Día No Laboral</h2>
        <p>{{ ucfirst($nombreMes) }} de {{ $anio }}</p>
    </div>

    <form method="GET" class="actions no-print">

        <div>
            <select name="mes">
                @for($m = 1; $m <= 12; $m++)
                    @php
                        $mesTexto = \Carbon\Carbon::createFromDate($anio, $m, 1)->translatedFormat('F');
                    @endphp
                    <option value="{{ $m }}" {{ (int)$mes == $m ? 'selected' : '' }}>
                        {{ ucfirst($mesTexto) }}
                    </option>
                @endfor
            </select>

            <select name="anio">
                @for($y = now()->year - 3; $y <= now()->year + 1; $y++)
                    <option value="{{ $y }}" {{ (int)$anio == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>

            <button class="btn btn-primary">Filtrar</button>
        </div>

        <div>
            <button type="button" onclick="window.print()" class="btn btn-gold">
                Imprimir
            </button>

            <a href="{{ route('compensatorios.solicitudes.index') }}" class="btn btn-primary">
                Volver
            </a>
        </div>

    </form>

    <div style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:13px;">
    <div>
        <strong>Total de solicitudes:</strong> {{ $solicitudes->count() }}
    </div>

    <div>
        <strong>Generado el:</strong> {{ now()->format('d-m-Y H:i') }}
    </div>
</div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Departamento</th>
                <th>Fecha solicitud</th>
                <th>Fecha trabajada</th>
                <th>Empleados</th>
                <th>Estado</th>
                <th class="text-left">Descripción</th>
            </tr>
        </thead>

        <tbody>

        @forelse($solicitudes as $sol)
            <tr>
                <td class="text-center">{{ $sol->id }}</td>

                <td class="text-center">
                    {{ $sol->departamento->nombre ?? '-' }}
                </td>

                <td class="text-center">
                    {{ \Carbon\Carbon::parse($sol->created_at)->format('d-m-Y') }}
                </td>

                <td class="text-center">
                    {{ \Carbon\Carbon::parse($sol->fecha_trabajada)->format('d-m-Y') }}
                </td>

                <td class="text-center">
                    {{ $sol->empleados->count() }}
                </td>

                <td class="text-center">
                    {{ ucfirst($sol->estado) }}
                </td>

                <td class="text-left">
                    {{ $sol->descripcion ?? '-' }}
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="7" class="text-center">
                    No hay solicitudes en este mes.
                </td>
            </tr>
        @endforelse

        </tbody>
    </table>
    <br>
    <div class="print-footer">
    Sistema RRHH - Municipalidad de Danlí
</div>

</div>

</body>
</html>