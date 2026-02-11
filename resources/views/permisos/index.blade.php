<!DOCTYPE html>
<html>
<head>
    <title>Listado de Permisos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

<h2>Listado de Permisos</h2>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Empleado</th>
            <th>Tipo</th>
            <th>Estado</th>
            <th>Fecha Inicio</th>
            <th>Fecha Fin</th>
            <th>Horas</th>
        </tr>
    </thead>
    <tbody>
        @foreach($permisos as $permiso)
        <tr>
            <td>
                {{ $permiso->empleado->nombres ?? '' }}
                {{ $permiso->empleado->apellidos ?? '' }}
            </td>
            <td>{{ $permiso->tipoPermiso->nombre ?? '' }}</td>
            <td>{{ $permiso->estado->nombre ?? '' }}</td>
            <td>{{ $permiso->fecha_inicio }}</td>
            <td>{{ $permiso->fecha_fin }}</td>
            <td>{{ $permiso->horas }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
