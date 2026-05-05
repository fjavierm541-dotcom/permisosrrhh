<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Permisos por mes</title>

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
            color: #f1f1f1;
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
            .no-print { display: none; }

            body {
                background: white;
                padding: 0;
            }

            .container {
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
            }

            table {
                font-size: 10.5px;
            }

            th, td {
                padding: 5px;
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
        <h2>Listado de Permisos</h2>
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

            <a href="{{ route('permisos.index') }}" class="btn btn-primary">
                Volver
            </a>
        </div>

    </form>

    <table>
        <thead>
            <tr>
                <th class="text-left">Empleado</th>
                <th>Tipo</th>
                <th>Modalidad</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Horas</th>
                <th>Estado</th>
            </tr>
        </thead>

        <tbody>
        @forelse($permisos as $permiso)
            <tr>
                <td class="text-left">
                    {{ $permiso->empleado->primer_nombre ?? '' }}
                    {{ $permiso->empleado->segundo_nombre ?? '' }}
                    {{ $permiso->empleado->primer_apellido ?? '' }}
                    {{ $permiso->empleado->segundo_apellido ?? '' }}
                </td>

                <td class="text-center">{{ $permiso->tipo->nombre ?? '' }}</td>
                <td class="text-center">{{ ucfirst(str_replace('_',' ', $permiso->modalidad)) }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($permiso->fecha_inicio)->format('d-m-Y') }}</td>

                <td class="text-center">
                    {{ $permiso->fecha_fin ? \Carbon\Carbon::parse($permiso->fecha_fin)->format('d-m-Y') : '-' }}
                </td>

                <td class="text-center">
                    {{ $permiso->modalidad == 'horas' ? $permiso->horas : '-' }}
                </td>

                <td class="text-center">{{ $permiso->estado->nombre ?? '' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">
                    No hay permisos en este mes.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

</div>

</body>
</html>