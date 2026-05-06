<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Permiso #{{ $permiso->id }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            color: #222;
        }

        h2 {
            margin-bottom: 5px;
        }

        .info {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        table th {
            background: #f1f1f1;
        }

        .firma {
            margin-top: 80px;
        }

        .linea {
            margin-top: 60px;
            width: 250px;
            border-top: 1px solid #000;
        }
    </style>
</head>

<body>

    <h2>Solicitud de Permiso Laboral</h2>

    <div class="info">
        <strong>Permiso:</strong> #{{ $permiso->id }}<br>

        <strong>Empleado:</strong>
        {{ $permiso->empleado->primer_nombre ?? '' }}
        {{ $permiso->empleado->segundo_nombre ?? '' }}
        {{ $permiso->empleado->primer_apellido ?? '' }}
        {{ $permiso->empleado->segundo_apellido ?? '' }}<br>

        <strong>DNI:</strong>
        {{ $permiso->dni_empleado }}<br>

        <strong>Tipo:</strong>
        {{ $permiso->tipo->nombre ?? '—' }}<br>

        <strong>Modalidad:</strong>
        {{ ucfirst(str_replace('_', ' ', $permiso->modalidad)) }}<br>

        <strong>Fecha inicio:</strong>
        {{ \Carbon\Carbon::parse($permiso->fecha_inicio)->format('d-m-Y') }}<br>

        <strong>Fecha fin:</strong>
        {{ $permiso->fecha_fin ? \Carbon\Carbon::parse($permiso->fecha_fin)->format('d-m-Y') : '—' }}<br>

        <strong>Horas:</strong>
        {{ $permiso->modalidad == 'horas' ? $permiso->horas : '—' }}<br>

        <strong>Estado:</strong>
        {{ $permiso->estado->nombre ?? 'Pendiente' }}
    </div>

    <strong>Motivo:</strong>

    <p>
        {{ $permiso->motivo ?? 'Sin motivo registrado.' }}
    </p>

    <strong>Documento adjunto:</strong>

    <p>
        @if($permiso->documento)
            Documento PDF adjunto en el sistema.
        @else
            Sin documento adjunto.
        @endif
    </p>

    <div class="firma">

        <div class="linea"></div>
        Firma RRHH

    </div>

    <script>
        window.print();
    </script>

</body>
</html>