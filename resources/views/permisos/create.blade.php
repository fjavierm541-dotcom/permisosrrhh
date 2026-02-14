@extends('layouts.master')

@section('title', 'Nombre de la Vista')

@section('content')

    <style>
        body {
            background: linear-gradient(135deg, #1f3a56, #2d4f73);
            min-height: 100vh;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-radius: 18px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
        }

        .card-header-custom {
            background-color: #1f3a56;
            color: white;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
        }

        .form-label {
            font-weight: 600;
            color: #1f3a56;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            padding: 10px;
        }

        .btn-primary-custom {
            background-color: #1f3a56;
            border: none;
        }

        .btn-primary-custom:hover {
            background-color: #162a40;
        }

        .btn-gold {
            background-color: #d4b06a;
            border: none;
            color: #1f3a56;
            font-weight: 600;
        }

        .btn-gold:hover {
            background-color: #c39a4f;
        }
    </style>
</head>

<body>

<div class="container py-5">

    <div class="glass-card">

        <div class="card-header-custom p-4">
            <h4 class="mb-0">Registrar Nuevo Permiso</h4>
        </div>

        <div class="p-4">

            <form method="POST" action="{{ route('permisos.store') }}">
                @csrf

                <div class="row">

                    <!-- EMPLEADO -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Empleado</label>
                        <select name="dni_empleado" class="form-select" required>
                            <option value="">Seleccione</option>
                            @foreach($empleados as $empleado)
                                <option value="{{ $empleado->DNI }}">
                                    {{ $empleado->primer_nombre }} {{ $empleado->primer_apellido }} - {{ $empleado->DNI }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- MODALIDAD -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Modalidad</label>
                        <select name="modalidad" id="modalidad" class="form-select" required>
                            <option value="">Seleccione</option>
                            <option value="horas">Por horas</option>
                            <option value="medio_dia">Medio día</option>
                            <option value="un_dia">Un día</option>
                            <option value="varios_dias">Varios días</option>
                        </select>
                    </div>

                    <!-- TIPO -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tipo de permiso</label>
                        <select name="tipo_permiso_id" class="form-select" required>
                            <option value="">Seleccione</option>
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo->id }}">
                                    {{ $tipo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- FECHA INICIO -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha inicio</label>
                        <input type="date" name="fecha_inicio" class="form-control" required>
                    </div>

                    <!-- FECHA FIN -->
                    <div class="col-md-6 mb-3" id="campo_fecha_fin" style="display:none;">
                        <label class="form-label">Fecha fin</label>
                        <input type="date" name="fecha_fin" class="form-control">
                    </div>

                    <!-- HORAS -->
                    <div class="col-md-6 mb-3" id="campo_horas" style="display:none;">
                        <label class="form-label">Cantidad de horas</label>
                        <input type="number" name="horas" class="form-control" min="1" max="8">
                    </div>

                    <!-- MOTIVO -->
                    <div class="col-12 mb-3">
                        <label class="form-label">Motivo</label>
                        <textarea name="motivo" class="form-control" rows="3"></textarea>
                    </div>

                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('permisos.index') }}" class="btn btn-outline-secondary me-2">
                        Cancelar
                    </a>
                    <button class="btn btn-gold px-4">
                        Guardar Permiso
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<script>
    const modalidad = document.getElementById('modalidad');
    const campoHoras = document.getElementById('campo_horas');
    const campoFechaFin = document.getElementById('campo_fecha_fin');

    modalidad.addEventListener('change', function() {

        campoHoras.style.display = 'none';
        campoFechaFin.style.display = 'none';

        if (this.value === 'horas') {
            campoHoras.style.display = 'block';
        }

        if (this.value === 'varios_dias') {
            campoFechaFin.style.display = 'block';
        }
    });
</script>

 
@endsection
