<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Permisos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
            <h4 class="mb-0">Gestión de Permisos</h4>

            <a href="{{ route('permisos.create') }}" class="btn btn-gold">
                + Nuevo Permiso
            </a>
        </div>

        <div class="p-4">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead>
                        <tr>
                            <th>Empleado</th>
                            <th>Modalidad</th>
                            <th>Tipo</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Horas</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($permisos as $permiso)
                            <tr>
                                <td>
    <div>
        <strong>
            {{ $permiso->empleado->primer_nombre ?? '' }}
            {{ $permiso->empleado->primer_apellido ?? '' }}
        </strong>
    </div>

    @php
        $saldo = $acumulados[$permiso->dni_empleado] ?? null;
    @endphp

    @if($saldo)
        <small class="text-muted">
            {{ $saldo->dias_vacacionales }} días -
            {{ $saldo->horas_acumuladas }} horas disponibles
        </small>
    @else
        <small class="text-muted">
            0 días - 0 horas disponibles
        </small>
    @endif
</td>


                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ ucfirst(str_replace('_',' ', $permiso->modalidad)) }}
                                    </span>
                                </td>

                                <td>{{ $permiso->tipo->nombre ?? '' }}</td>
                                <td>{{ $permiso->fecha_inicio }}</td>
                                <td>{{ $permiso->fecha_fin ?? '-' }}</td>
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

        </div>
    </div>
</div>

<!-- MODAL CONFIRMACION -->
<div class="modal fade" id="confirmacionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header" style="background-color:#274769; color:white;">
                <h5 class="modal-title" id="modalTitulo"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p id="modalMensaje"></p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <a href="#" id="btnConfirmar" class="btn">
                    Confirmar
                </a>
            </div>

        </div>
    </div>
</div>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- SCRIPT MODAL -->
<script>
    function abrirModal(accion, id) {

        const modal = new bootstrap.Modal(document.getElementById('confirmacionModal'));
        const titulo = document.getElementById('modalTitulo');
        const mensaje = document.getElementById('modalMensaje');
        const boton = document.getElementById('btnConfirmar');

        if (accion === 'aprobar') {
            titulo.textContent = "Confirmar Aprobación";
            mensaje.textContent = "¿Está seguro que desea aprobar este permiso?";
            boton.className = "btn btn-success";
            boton.href = "/permisos/" + id + "/aprobar";
        }

        if (accion === 'rechazar') {
            titulo.textContent = "Confirmar Rechazo";
            mensaje.textContent = "¿Está seguro que desea rechazar este permiso?";
            boton.className = "btn btn-danger";
            boton.href = "/permisos/" + id + "/rechazar";
        }

        modal.show();
    }
</script>

</body>
</html>
