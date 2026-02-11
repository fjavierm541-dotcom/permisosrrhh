<!DOCTYPE html>
<html>
<head>
    <title>Crear Permiso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

<h2>Crear Permiso</h2>

<form method="POST" action="{{ route('permisos.store') }}">
    @csrf

    <div class="mb-3">
        <label>Empleado</label>
        <select name="dni_empleado" class="form-control">
    @foreach($empleados as $empleado)
        <option value="{{ $empleado->DNI }}">
            {{ $empleado->primer_nombre }} {{ $empleado->primer_apellido }} - {{ $empleado->DNI }}
        </option>
    @endforeach
</select>

    </div>

    <div class="mb-3">
        <label>Tipo de Permiso</label>
        <select name="tipo_permiso_id" class="form-control">
            @foreach($tipos as $tipo)
                <option value="{{ $tipo->id }}">
                    {{ $tipo->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Fecha Inicio</label>
        <input type="date" name="fecha_inicio" class="form-control">
    </div>

    <div class="mb-3">
        <label>Fecha Fin</label>
        <input type="date" name="fecha_fin" class="form-control">
    </div>

    <div class="mb-3">
        <label>Horas (si aplica)</label>
        <input type="number" name="horas" class="form-control">
    </div>

    <div class="mb-3">
        <label>Motivo</label>
        <textarea name="motivo" class="form-control"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>
</form>

</body>
</html>
