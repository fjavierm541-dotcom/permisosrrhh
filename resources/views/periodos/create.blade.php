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
            border-radius: 18px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
        }

        .card-header-custom {
            background-color: #274769;
            color: white;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
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
            <h4 class="mb-0">Registrar Períodos Vacacionales</h4>
        </div>

        <div class="p-4">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('periodos.store') }}">
                @csrf

                <!-- Empleado -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Empleado</label>
                    <select name="dni_empleado" class="form-select" required>
                        <option value="">Seleccione</option>
                        @foreach($empleados as $empleado)
                            <option value="{{ $empleado->DNI }}">
                                {{ $empleado->primer_nombre }} {{ $empleado->primer_apellido }} - {{ $empleado->DNI }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="contenedor-periodos">

                    <div class="periodo-item border rounded p-3 mb-3">

                        <div class="row">

                            <div class="col-md-3 mb-3">
                                <label>Año laboral</label>
                                <input type="number" name="anio_laboral[]" class="form-control" required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Días otorgados</label>
                                <input type="number" name="dias_otorgados[]" class="form-control" required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Días usados</label>
                                <input type="number" name="dias_usados[]" class="form-control" value="0">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Fecha inicio período</label>
                                <input type="date" name="fecha_inicio_periodo[]" class="form-control" required>
                            </div>

                        </div>

                    </div>

                </div>

                <button type="button" class="btn btn-secondary mb-3" onclick="agregarPeriodo()">
                    + Agregar otro año
                </button>

                <div class="text-end">
                    <button class="btn btn-gold">
                        Guardar Períodos
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<script>
function agregarPeriodo() {

    let contenedor = document.getElementById('contenedor-periodos');

    let nuevo = `
        <div class="periodo-item border rounded p-3 mb-3">
            <div class="row">

                <div class="col-md-3 mb-3">
                    <label>Año laboral</label>
                    <input type="number" name="anio_laboral[]" class="form-control" required>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Días otorgados</label>
                    <input type="number" name="dias_otorgados[]" class="form-control" required>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Días usados</label>
                    <input type="number" name="dias_usados[]" class="form-control" value="0">
                </div>

                <div class="col-md-3 mb-3">
                    <label>Fecha inicio período</label>
                    <input type="date" name="fecha_inicio_periodo[]" class="form-control" required>
                </div>

            </div>
        </div>
    `;

    contenedor.insertAdjacentHTML('beforeend', nuevo);
}
</script>

@endsection