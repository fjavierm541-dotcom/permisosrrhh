@extends('layouts.master')

@section('title', 'Permisos')

@section('content')


    <style>
        body {
            background: linear-gradient(135deg, #1f3a56, #2d4f73);
            min-height: 100vh;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(8px);
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

        table th {
            background-color: #2d4f73 !important;
            color: white;
        }
    </style>
</head>

<body>

<div class="container py-5">

    <div class="glass-card"> 

        <div class="card-header-custom p-4 d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Listado de Permisos</h4>

    <div class="d-flex gap-2">

            
        <a href="{{ route('permisos.create') }}" class="btn btn-gold">
            + Nuevo Permiso
        </a>

        <a href="{{ route('permisos.imprimir.mes') }}" class="btn btn-outline-light">
            Imprimir por mes
        </a>

         <a href="{{ route('permisos.menu') }}" class="btn btn-outline-light">
            Volver
        </a>

    </div>
</div>

        <div class="p-4">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif


            <form id="formBusquedaPermisos" method="GET" action="{{ route('permisos.index') }}" class="mb-3">

    <div class="row g-2 align-items-end">

        <div class="col-md-5">
            <label class="form-label">Buscar</label>
            <input type="text"
                   id="buscarPermisoInput"
                   name="buscar"
                   class="form-control"
                   placeholder="Empleado, DNI, modalidad o tipo"
                   value="{{ request('buscar') }}">
        </div>

        <div class="col-md-2">
            <label class="form-label">Desde</label>
            <input type="date"
                   id="fechaDesdeInput"
                   name="fecha_desde"
                   class="form-control"
                   value="{{ request('fecha_desde') }}">
        </div>

        <div class="col-md-2">
            <label class="form-label">Hasta</label>
            <input type="date"
                   id="fechaHastaInput"
                   name="fecha_hasta"
                   class="form-control"
                   value="{{ request('fecha_hasta') }}">
        </div>

        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary-custom w-100">
                Buscar
            </button>

            <a href="{{ route('permisos.index') }}" class="btn btn-outline-secondary w-100">
                Limpiar
            </a>
        </div>

    </div>

</form>



            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead>
                        <tr>
                            <th>Empleado</th>
                            <th>Modalidad</th>
                            <th>Tipo</th>
                            <th>Fecha inicial</th>
                            <th>Fecha final</th>
                            <th>Horas</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($permisos as $permiso)
                            <tr>
                                <td class="text-start">
                                        {{ $permiso->empleado->primer_nombre ?? '' }} {{ $permiso->empleado->segundo_nombre ?? '' }}
                                        {{ $permiso->empleado->primer_apellido ?? '' }}  {{ $permiso->empleado->segundo_apellido ?? '' }}

                                </td>


                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ ucfirst(str_replace('_',' ', $permiso->modalidad)) }}
                                    </span>
                                </td>

                                <td>{{ $permiso->tipo->nombre ?? '' }}</td>
                                <td>{{ \Carbon\Carbon::parse($permiso->fecha_inicio)->format('d-m-Y') }}</td>
                                <td>
                                    {{ $permiso->fecha_fin ? \Carbon\Carbon::parse($permiso->fecha_fin)->format('d-m-Y') : '-' }}
                                </td>
                                <td>{{ $permiso->modalidad == 'horas' ? $permiso->horas : '-' }}</td>

                                <td>
                                    @if($permiso->estado->nombre == 'Pendiente')
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                    @elseif($permiso->estado->nombre == 'Aprobado')
                                        <span class="badge bg-success">Aprobado</span>
                                    @else
                                        <span class="badge bg-danger">Rechazado</span>
                                    @endif
                                </td>

                                <td>
                                    @if($permiso->estado->nombre == 'Pendiente')

                                        <button class="btn btn-sm btn-success"
                                            onclick="abrirModal('aprobar', {{ $permiso->id }})">
                                            Aprobar
                                        </button>

                                        <button class="btn btn-sm btn-danger"
                                            onclick="abrirModal('rechazar', {{ $permiso->id }})">
                                            Rechazar
                                        </button>

                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-muted">
                                    No hay permisos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

                
            </div>
<div class="d-flex justify-content-end mt-3">
    {{ $permisos->links() }}
</div>
        </div>
        
    </div>
    
</div>


<!-- MODAL CONFIRMACION -->
<div class="modal fade" id="confirmacionModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="formConfirmacion">
            @csrf

            <div class="modal-content">

                <div class="modal-header" style="background-color:#274769; color:white;">
                    <h5 class="modal-title" id="modalTitulo"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p id="modalMensaje"></p>

                    <div class="mb-3 d-none" id="bloqueMotivoRechazo">
                        <label class="form-label fw-bold">Motivo del rechazo</label>
                        <textarea 
                            name="motivo_rechazo" 
                            id="motivo_rechazo"
                            class="form-control"
                            rows="3"
                        ></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" id="btnConfirmar" class="btn">
                        Confirmar
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>



<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function abrirModal(accion, id) {

        const modal = new bootstrap.Modal(document.getElementById('confirmacionModal'));
        const titulo = document.getElementById('modalTitulo');
        const mensaje = document.getElementById('modalMensaje');
        const boton = document.getElementById('btnConfirmar');
        const form = document.getElementById('formConfirmacion');
        const bloqueMotivo = document.getElementById('bloqueMotivoRechazo');
        const motivo = document.getElementById('motivo_rechazo');

        motivo.value = '';
        motivo.required = false;
        bloqueMotivo.classList.add('d-none');

        if (accion === 'aprobar') {
            titulo.textContent = "Confirmar Aprobación";
            mensaje.textContent = "¿Está seguro que desea aprobar este permiso?";
            boton.className = "btn btn-success";
            form.action = "/permisos/" + id + "/aprobar";
        }

        if (accion === 'rechazar') {
            titulo.textContent = "Confirmar Rechazo";
            mensaje.textContent = "Ingrese el motivo por el cual se rechazará este permiso.";
            boton.className = "btn btn-danger";
            form.action = "/permisos/" + id + "/rechazar";

            bloqueMotivo.classList.remove('d-none');
            motivo.required = true;
        }

        modal.show();
    }
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('formBusquedaPermisos');
    const buscar = document.getElementById('buscarPermisoInput');
    const desde = document.getElementById('fechaDesdeInput');
    const hasta = document.getElementById('fechaHastaInput');

    let timer = null;

    function buscarAutomatico() {
        clearTimeout(timer);

        timer = setTimeout(() => {
            form.submit();
        }, 900);
    }

    buscar.addEventListener('keyup', buscarAutomatico);

    desde.addEventListener('change', function () {
        form.submit();
    });

    hasta.addEventListener('change', function () {
        form.submit();
    });

});
</script>

@endsection
