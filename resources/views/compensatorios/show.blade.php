@extends('layouts.master')

@section('title', 'Detalle Solicitud')

@section('content')

<div class="glass-card p-0 overflow-hidden">

    <div class="px-4 py-3" style="background:#2d4f73; color:white;">
        <h5 class="mb-0">
            Solicitud del departamento:
            {{ $solicitud->departamento->nombre ?? '—' }}
        </h5>
    </div>

    <div class="p-4">


     <p>
            <strong>Estado:</strong>
            @if($solicitud->estado == 'pendiente')
                <span class="badge bg-warning text-dark">Pendiente</span>
            @elseif($solicitud->estado == 'aprobado')
                <span class="badge bg-success">Aprobado</span>
            @else
                <span class="badge bg-danger">Rechazado</span>
            @endif
        </p>

        <p>
            <strong>Fecha solicitada a laborar:</strong><br>
            {{ \Carbon\Carbon::parse($solicitud->fecha_trabajada)
                ->locale('es')
                ->translatedFormat('l j \\d\\e F \\d\\e\\l Y') }}
        </p>

       


        <p>
            <strong>Fecha en que se hizo la solicitud:</strong><br>
            {{ \Carbon\Carbon::parse($solicitud->created_at)
                ->locale('es')
                ->translatedFormat('l j \\d\\e F \\d\\e\\l Y') }}
        </p>

        <p>
            <strong>Descripción:</strong><br>
            {{ $solicitud->descripcion ?? 'Sin descripción registrada.' }}
        </p>

        <p>
            <strong>Justificación:</strong><br>
            {{ $solicitud->justificacion ?? 'Sin justificación registrada.' }}
        </p>

        <hr>

        <h6>Empleados incluidos</h6>

        <ul class="mb-3">
    @foreach($solicitud->empleados as $emp)
        <li>
            {{ $emp->empleado->primer_nombre ?? '' }}
            {{ $emp->empleado->segundo_nombre ?? '' }}
            {{ $emp->empleado->primer_apellido ?? '' }}
            {{ $emp->empleado->segundo_apellido ?? '' }}
            ({{ $emp->dni_empleado }})
        </li>
    @endforeach
</ul>

        @if($solicitud->estado == 'pendiente')

            <form method="POST" action="{{ route('compensatorios.solicitudes.aprobar', $solicitud->id) }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Días a aprobar</label>

                    <select name="dias_aprobados"
                            class="form-control @error('dias_aprobados') field-invalid @enderror"
                            required>
                        <option value="">Seleccione</option>
                        <option value="1" {{ old('dias_aprobados') == 1 ? 'selected' : '' }}>1 día</option>
                        <option value="2" {{ old('dias_aprobados') == 2 ? 'selected' : '' }}>2 días</option>
                    </select>

                    @error('dias_aprobados')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        Aprobar
                    </button>

                    <button type="button"
                            class="btn btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#modalRechazo">
                        Rechazar
                    </button>

                    <a href="{{ route('compensatorios.solicitudes.index') }}"
                       class="btn btn-secondary">
                        Volver
                    </a>
                </div>
            </form>

        @else

            <a href="{{ route('compensatorios.solicitudes.index') }}"
               class="btn btn-secondary">
                Volver
            </a>

        @endif

    </div>
</div>

<!-- MODAL RECHAZO -->
<div class="modal fade" id="modalRechazo" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('compensatorios.solicitudes.rechazar', $solicitud->id) }}">
            @csrf

            <div class="modal-content">

                <div class="modal-header" style="background:#2d4f73; color:white;">
                    <h5 class="modal-title">Rechazar solicitud</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label">Justificación del rechazo</label>

                    <textarea name="motivo_rechazo"
                              class="form-control"
                              rows="4"
                              required>{{ old('motivo_rechazo') }}</textarea>

                    @error('motivo_rechazo')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-danger">
                        Confirmar rechazo
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection