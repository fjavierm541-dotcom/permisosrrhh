@extends('layouts.master')

@section('title', 'Detalle del Empleado')

@section('content')

<div class="glass-card p-4 mb-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">
            Detalle del Empleado
        </h4>



        <div class="d-flex gap-2">
    <a href="{{ route('empleados.index') }}" class="btn btn-outline-dark btn-sm">
        ← Volver
    </a>

    <a href="{{ route('empleados.reporte', $empleado->DNI) }}" 
   target="_blank"
   class="btn btn-outline-dark btn-sm">
   Imprimir
</a>

</div>

    </div>

    <hr>

    <!-- DATOS GENERALES -->
<div class="row mb-4">

    <div class="col-md-4">
        <strong>Nombre:</strong><br>
        {{ $empleado->primer_nombre }} {{ $empleado->primer_apellido }}
    </div>

    <div class="col-md-4">
        <strong>DNI:</strong><br>
        {{ $empleado->DNI }}
    </div>

    <div class="col-md-4">
        <strong>Fecha nombramiento:</strong><br>
        {{ \Carbon\Carbon::parse($empleado->fecha_nombramiento)
            ->locale('es')
            ->translatedFormat('d \d\e F \d\e\l Y') }}
    </div>

</div>

<div class="row mb-3">

    <div class="col-md-4">
        <strong>Puesto de nombramiento:</strong><br>
        {{ $empleado->puesto ?? 'No registrado' }}
    </div>

    <div class="col-md-4">
        <strong>Puesto actual:</strong><br>
        <span class="text-muted">{{ $empleado->departamentoFuncional->nombre ?? 'Sin asignar' }}</span>
    </div>

</div>


<!-- RESUMEN DISPONIBLE -->
<div class="alert alert-info">

    <div class="mt-2">
        <strong>Total de días disponibles: {{ $totalGeneral }} </strong>
    </div>

    <div class="mt-2">
        Compensatorios: <strong>{{ $diasCompensatorios }}</strong> días<br>
        Vacaciones: <strong>{{ $totalDiasDisponibles }}</strong> días
    </div>

    

    <small class="text-muted d-block mt-2">
        * Los días compensatorios se utilizan primero automáticamente al solicitar permisos.
    </small>

</div>


<!-- PERÍODOS ACTIVOS -->
<div class="glass-card p-4 mb-4">

    <h5 class="mb-1">Períodos Activos</h5>
    <small class="text-muted">Histórico de días asignados por año.</small>

    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">

            <thead>
                <tr>
                    <th>Año</th>
                    <th>Vacaciones</th>
                    <th>Compensatorios</th>
                    <th>Total acumulados</th>
                    <th>Vencimiento</th>
                    <th>Detalle</th>
                </tr>
            </thead>

            <tbody>
                @forelse($periodosActivos as $periodo)

                    @php
                        $anio = $periodo->anio_laboral;

                        // 🟡 Vacaciones (dato fijo)
                        $vacaciones = $periodo->dias_otorgados;

                        // 🟢 Compensatorios acumulados del año
                        $compensatorios = $diasCompensatoriosPorAnio[$anio] ?? 0;

                        // 🔵 Total histórico
                        $total = $vacaciones + $compensatorios;
                    @endphp

                    <tr>
                        <td>{{ $anio }}</td>

                        <td>{{ $vacaciones }}</td>

                        <td>{{ $compensatorios }}</td>

                        <td class="fw-bold">{{ $total }}</td>

                        <td>
                            {{ \Carbon\Carbon::parse($periodo->extension_hasta ?? $periodo->fecha_vencimiento)->format('d-m-y') }}
                        </td>

                        <td>
                            @if($periodo->estado == 'extendido')
                                <span class="badge bg-info">Extendido</span>

                                <button 
                                    class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDetalleExtension"
                                    data-motivo="{{ $periodo->motivo_extension }}"
                                    data-documento="{{ $periodo->documento_extension }}"
                                >
                                    Ver detalle
                                </button>
                            @endif
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6">No hay períodos activos.</td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>



<!-- PERÍODOS VENCIDOS -->
<div class="glass-card p-4 mb-4">

    <h5 class="mb-3 text-danger">Períodos Vencidos</h5>
    <small class="text-muted">Año(s) en que los  días libres vencieron.</small>

    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead>
                <tr>
                    <th>Año</th>
                    <th>Vacaciones</th>
                    <th>Compensatorios</th>
                    <th>Total</th>
                    <th>Días usados</th>
                    <th>Vencimiento</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($periodosVencidos as $periodo)

                    @php
                        $anio = $periodo->anio_laboral;

                        // 🟡 Vacaciones
                        $vacaciones = $periodo->dias_otorgados;

                        // 🟢 Compensatorios del año
                        $compensatorios = $diasCompensatoriosPorAnio[$anio] ?? 0;

                        // 🔵 Total histórico
                        $total = $vacaciones + $compensatorios;

                        // 🔴 Usados (tal cual tu sistema)
                        $usados = $periodo->dias_usados;
                    @endphp

                    <tr class="table-danger">

                        <td>{{ $anio }}</td>

                        <td>{{ $vacaciones }}</td>

                        <td>{{ $compensatorios }}</td>

                        <td class="fw-bold">{{ $total }}</td>

                        <td>{{ $usados }}</td>

                        <td>
                            {{ \Carbon\Carbon::parse($periodo->extension_hasta ?? $periodo->fecha_vencimiento)->format('d-m-Y') }}
                        </td>

                        <td>
                            @if($periodo->estado == 'vencido')
                                <button class="btn btn-sm btn-warning" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalReactivar"
                                    data-id="{{ $periodo->id }}">
                                    Reactivar
                                </button>
                            @endif
                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="7">No hay períodos vencidos.</td>
                    </tr>
                @endforelse
                </tbody>
        </table>
    </div>

</div>



<!-- HISTORIAL DE MOVIMIENTOS -->
<div class="glass-card p-4">

	<h5 class="mb-3">Historial de Movimientos</h5>

	<div class="table-responsive">
		<table class="table table-bordered table-hover text-center">
			<thead>
				<tr>
					<th>Fecha de gestión</th>
					<th>Tipo</th>
					<th>Estado</th>
					<th>Rango</th>
					<th>Días</th>
					<th>Descripción</th>
					<th>Aprobado por</th>
				</tr>
			</thead>

			<tbody>
				@forelse($movimientos as $movimiento)

					@php
						$permiso = $permisos[$movimiento->permiso_id] ?? null;

						$tipoMovimiento = strtolower($movimiento->tipo_movimiento ?? '');
						$categoria = strtolower($movimiento->categoria ?? '');

						// 🔹 Tipo visible
						if ($tipoMovimiento === 'descuento_calendario') {
							$tipoVisible = 'Descuento calendario';
						} elseif ($tipoMovimiento === 'asignacion_calendario') {
							$tipoVisible = 'Asignación calendario';
						} elseif ($tipoMovimiento === 'asignacion') {
							$tipoVisible = 'Asignación';
						} elseif (!empty($categoria)) {
							$tipoVisible = ucfirst($categoria);
						} elseif ($permiso && $permiso->tipo) {
							$tipoVisible = $permiso->tipo->nombre;
						} else {
							$tipoVisible = 'Movimiento';
						}

						// 🔹 Estado
						if ($permiso && $permiso->estado) {
							$estadoVisible = $permiso->estado->nombre;
						} elseif ($tipoMovimiento === 'descuento_calendario' || $tipoMovimiento === 'asignacion_calendario') {
							$estadoVisible = 'Automático';
						} elseif ($tipoMovimiento === 'asignacion') {
							$estadoVisible = 'Asignado';
						} else {
							$estadoVisible = '—';
						}

						// 🔹 Rango
						$fechaInicio = null;
						$fechaFin = null;

						if ($permiso && !empty($permiso->fecha_inicio)) {
							$fechaInicio = \Carbon\Carbon::parse($permiso->fecha_inicio)->format('d-m-Y');
						}

						if ($permiso && !empty($permiso->fecha_fin)) {
							$fechaFin = \Carbon\Carbon::parse($permiso->fecha_fin)->format('d-m-Y');
						}

						// 🔹 Aprobado por (preparado para usuarios)
						$aprobadoPor = '—';
					@endphp

					<tr>
						<td>{{ \Carbon\Carbon::parse($movimiento->created_at)->format('d-m-Y H:i') }}</td>

						<td>{{ $tipoVisible }}</td>

						<td>{{ $estadoVisible }}</td>

						<td>
							@if($fechaInicio)
								{{ $fechaInicio }}
								@if($fechaFin && $fechaFin !== $fechaInicio)
									<br>
									{{ $fechaFin }}
								@endif
							@else
								—
							@endif
						</td>

						<td>{{ $movimiento->dias_afectados ?? 0 }}</td>

						<td>{{ $movimiento->descripcion ?? '—' }}</td>

						<td>{{ $aprobadoPor }}</td>
					</tr>

				@empty
					<tr>
						<td colspan="7">No hay movimientos registrados.</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>

</div>



<!-- MODAL REACTIVAR -->
<div class="modal fade" id="modalReactivar" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('periodos.reactivar') }}" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="periodo_id" id="periodo_id">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reactivar período</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Justificación</label>
                        <textarea name="motivo" class="form-control" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Documento (opcional)</label>
                        <input type="file" name="documento" class="form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-gold">Confirmar</button>
                </div>
            </div>
        </form>
    </div>
</div>




<div class="modal fade" id="modalDetalleExtension" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Detalle de extensión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="fw-bold">Motivo:</label>
                    <p id="modalMotivo" class="text-muted"></p>
                </div>

                <div>
                    <label class="fw-bold">Documento:</label><br>
                    <a href="#" target="_blank" id="modalDocumento" class="btn btn-sm btn-outline-secondary">
                        Ver documento
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('modalReactivar');

    modal.addEventListener('show.bs.modal', function (event) {

        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');

        document.getElementById('periodo_id').value = id;
    });

});
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('modalDetalleExtension');

    modal.addEventListener('show.bs.modal', function (event) {

        let button = event.relatedTarget;

        let motivo = button.getAttribute('data-motivo');
        let documento = button.getAttribute('data-documento');

        // 🔹 MOTIVO
        document.getElementById('modalMotivo').textContent = motivo && motivo.trim() !== '' 
            ? motivo 
            : 'Sin motivo registrado';

        // 🔹 DOCUMENTO
        let link = document.getElementById('modalDocumento');

        if (documento && documento.trim() !== '') {
            link.href = '/storage/' + documento;
            link.style.display = 'inline-block';
        } else {
            link.style.display = 'none';
        }

    });

});
</script>

@endsection
