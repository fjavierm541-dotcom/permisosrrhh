@extends('layouts.master')

@section('title', 'Nuevo Permiso')

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
            <h4 class="mb-0">Solicitar Nuevo permiso</h4>
        </div>

        <div class="p-4">

            <form method="POST" action="{{ route('permisos.store') }}" enctype="multipart/form-data">
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
<!-- PERMISO POR HORAS -->
<div class="col-md-12 mb-3" id="campo_horas" style="display:none;">

    <label class="form-label fw-bold">
        Permiso por horas
    </label>

    <div class="row g-3 mt-1">

        {{-- HORA DE SALIDA --}}
        <div class="col-md-4">

            <label class="form-label small fw-semibold">
                Hora de salida
            </label>

            <div class="d-flex align-items-center gap-2">

                {{-- HORA --}}
                <select id="hora_salida_hora"
                        class="form-select">

                    @for($h = 6; $h <= 18; $h++)

                       <option value="{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}">
                            {{ str_pad($h, 2, '0', STR_PAD_LEFT) }}
                        </option>

                    @endfor

                </select>

                <span class="fw-bold">:</span>

                {{-- MINUTOS --}}
                <select id="hora_salida_minuto"
                        class="form-select">

                    <option value="00">00</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="50">50</option>

                </select>

            </div>

        </div>

        {{-- HORA DE ENTRADA --}}
        <div class="col-md-4">

            <label class="form-label small fw-semibold">
                Hora de entrada
            </label>

            <div class="d-flex align-items-center gap-2">

                {{-- HORA --}}
                <select id="hora_entrada_hora"
                        class="form-select">

                    @for($h = 6; $h <= 18; $h++)

                        <option value="{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}">
                            {{ str_pad($h, 2, '0', STR_PAD_LEFT) }}
                        </option>

                    @endfor

                </select>

                <span class="fw-bold">:</span>

                {{-- MINUTOS --}}
                <select id="hora_entrada_minuto"
                        class="form-select">

                    <option value="00">00</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="50">50</option>

                </select>

            </div>

        </div>

        {{-- TIEMPO CALCULADO --}}
        <div class="col-md-4">

            <label class="form-label small fw-semibold">
                Tiempo solicitado
            </label>

            <input type="text"
                   id="tiempo_calculado"
                   class="form-control"
                   placeholder="0h 0min"
                   readonly>

            <small id="error_horario"
                   class="text-danger d-none">
                La hora de entrada debe ser mayor que la salida.
            </small>

        </div>

    </div>

    {{-- CAMPOS OCULTOS --}}
    <input type="hidden" name="horas" id="horas_decimal">

    <input type="hidden" name="hora_salida" id="hora_salida_hidden">

    <input type="hidden" name="hora_entrada" id="hora_entrada_hidden">

</div>

                    <!-- MOTIVO -->
                    <div class="col-12 mb-3">
    <label class="form-label">
        Motivo
        <small class="text-muted">(Razón en caso de ser laboral, personal o compensatorio)</small>
    </label>

    <textarea
        name="motivo"
        class="form-control"
        rows="4"
        maxlength="500"
    >{{ old('motivo') }}</textarea>

    @error('motivo')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>
<div class="col-12 mb-3">
    <label class="form-label">Documento de soporte PDF (opcional)</label>

    <input type="file"
           name="documento"
           class="form-control"
           accept="application/pdf">

    <small class="text-muted">
        Solo se permite archivo PDF.
    </small>

    @error('documento')
        <small class="text-danger d-block">{{ $message }}</small>
    @enderror
</div>
                </div>

                <div class="d-flex justify-content-end mt-4">

    <a href="{{ route('permisos.index') }}"
        class="btn btn-outline-secondary me-2">

        Cancelar

    </a>

    <button class="btn btn-gold px-4">

        Enviar solicitud

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

    const tiempoCalculado = document.getElementById('tiempo_calculado');

    const horasDecimal = document.getElementById('horas_decimal');

    const horaSalidaHidden = document.getElementById('hora_salida_hidden');
    const horaEntradaHidden = document.getElementById('hora_entrada_hidden');

    const errorHorario = document.getElementById('error_horario');

    modalidad.addEventListener('change', function () {

        campoHoras.style.display = 'none';
        campoFechaFin.style.display = 'none';

        if (this.value === 'horas') {
            campoHoras.style.display = 'block';
        }

        if (this.value === 'varios_dias') {
            campoFechaFin.style.display = 'block';
        }

    });

    function calcularTiempo() {

        const salidaHora =
            document.getElementById('hora_salida_hora').value;

        const salidaMinuto =
            document.getElementById('hora_salida_minuto').value;

        const entradaHora =
            document.getElementById('hora_entrada_hora').value;

        const entradaMinuto =
            document.getElementById('hora_entrada_minuto').value;

        const horaSalida =
            `${salidaHora}:${salidaMinuto}`;

        const horaEntrada =
            `${entradaHora}:${entradaMinuto}`;

        horaSalidaHidden.value = horaSalida;
        horaEntradaHidden.value = horaEntrada;

        const salida =
            new Date(`1970-01-01T${horaSalida}:00`);

        const entrada =
            new Date(`1970-01-01T${horaEntrada}:00`);

        let diferencia =
            (entrada - salida) / 1000 / 60;

        // VALIDAR HORARIO
        if (diferencia <= 0) {

            tiempoCalculado.value = '';

            horasDecimal.value = '';

            errorHorario.classList.remove('d-none');

            return;
        }

        errorHorario.classList.add('d-none');

        const horas =
            Math.floor(diferencia / 60);

        const minutos =
            diferencia % 60;

        tiempoCalculado.value =
            `${horas}h ${minutos}min`;

        horasDecimal.value =
            (diferencia / 60).toFixed(2);
    }

    document
        .querySelectorAll(
            '#hora_salida_hora, #hora_salida_minuto, #hora_entrada_hora, #hora_entrada_minuto'
        )
        .forEach(el => {

            el.addEventListener('change', calcularTiempo);

        });

</script>
 
@endsection
