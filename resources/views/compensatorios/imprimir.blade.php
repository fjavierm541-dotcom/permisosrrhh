<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud #{{ $solicitud->id }}</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            margin:40px;
            color:#222;
        }

        h2{
            margin-bottom:5px;
        }

        .info{
            margin-bottom:20px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }

        table th,
        table td{
            border:1px solid #ccc;
            padding:10px;
            text-align:left;
        }

        table th{
            background:#f1f1f1;
        }

        .firma{
            margin-top:80px;
        }

        .linea{
            margin-top:60px;
            width:250px;
            border-top:1px solid #000;
        }
    </style>
</head>
<body>

    <h2>Solicitud de Trabajo en Día No Laboral</h2>

    <div class="info">
        <strong>Solicitud:</strong> #{{ $solicitud->id }}<br>

        <strong>Departamento:</strong>
        {{ $solicitud->departamento->nombre ?? '—' }}<br>

        <strong>Fecha solicitada:</strong>
        {{ \Carbon\Carbon::parse($solicitud->fecha_trabajada)->format('d-m-Y') }}<br>

        <strong>Fecha de creación:</strong>
        {{ $solicitud->created_at->format('d-m-Y') }}<br>

        <strong>Estado:</strong>
        {{ ucfirst($solicitud->estado) }}
    </div>

    <strong>Descripción:</strong>

    <p>
        {{ $solicitud->descripcion ?? 'Sin descripción.' }}
    </p>

    <strong>Justificación:</strong>

    <p>
        {{ $solicitud->justificacion ?? 'Sin justificación.' }}
    </p>

    <h4>Empleados incluidos</h4>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Empleado</th>
                <th>DNI</th>
            </tr>
        </thead>

        <tbody>

            @foreach($solicitud->empleados as $i => $emp)

                <tr>
                    <td>{{ $i + 1 }}</td>

                    <td>
                        {{ $emp->empleado->primer_nombre ?? '' }}
                        {{ $emp->empleado->segundo_nombre ?? '' }}
                        {{ $emp->empleado->primer_apellido ?? '' }}
                        {{ $emp->empleado->segundo_apellido ?? '' }}
                    </td>

                    <td>{{ $emp->dni_empleado }}</td>
                </tr>

            @endforeach

        </tbody>
    </table>

    <div class="firma">

        <div class="linea"></div>
        Firma RRHH

    </div>

    <script>
        window.print();
    </script>

</body>
</html>