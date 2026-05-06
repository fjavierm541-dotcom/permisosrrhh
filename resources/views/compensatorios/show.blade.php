@extends('layouts.master')

@section('title', 'Detalle Solicitud')

@section('content')

<style>
    .info-box {
        background: #f8f9fa;
        border-radius: 14px;
        padding: 18px;
        height: 100%;
        border: 1px solid #e1e5ea;
    }

    .info-label {
        font-weight: 700;
        color: #1f3a56;
        margin-bottom: 5px;
    }

    .btn-gold {
        background-color: #d4b06a;
        border: none;
        color: #1f3a56;
        font-weight: 600;
    }

    .btn-gold:hover {
        background-color: #c39a4f;
        color: #1f3a56;
    }
</style>

<div class="glass-card p-0 overflow-hidden">

    <div class="px-4 py-3 d-flex justify-content-between align-items-center"
         style="background:#2d4f73; color:white;">
        <h5 class="mb-0">
            Solicitud del departamento: {{ $solicitud->departamento->nombre ?? '—' }}
        </h5>

        @if($solicitud->estado == 'pendiente')
            <span class="badge bg-warning text-dark">Pendiente</span>
        @elseif($solicitud->estado == 'aprobado')
            <span class="badge bg-success">Aprobado</span>
        @else
            <span class="badge bg-danger">Rechazado</span>
        @endif
    </div>

    <div class="p-4">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row g-3 mb-4">

            <div class="col-md-6">
                <div class="info-box">
                    <div class="info-label">Fecha solicitada a laborar</div>
                    <div>
                        {{ \Carbon\Carbon::parse($solicitud->fecha_trabajada)
                            ->locale('es')
                            ->translatedFormat('l j \\d\\e F \\d\\e\\l Y') }}
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-box">
                    <div class="info-label">Fecha en que se hizo la solicitud</div>
                    <div>
                        {{ \Carbon\Carbon::parse($solicitud->created_at)
                            ->locale('es')
                            ->translatedFormat('l j \\d\\e F \\d\\e\\l Y') }}
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-box">
                    <div class="info-label">Descripción</div>
                    <div>
                        {{ $solicitud->descripcion ?? 'Sin descripción registrada.' }}
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-box">
                    <div class="info-label">Justificación</div>
                    <div>
                        {{ $solicitud->justificacion ?? 'Sin justificación registrada.' }}
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-box">
                    <div class="info-label">Documento adjunto</div>

                    @if($solicitud->documento_path)
                        <a href="{{ asset('storage/' . $solicitud->documento_path) }}"
                           target="_blank"
                           class="btn btn-sm btn-outline-primary">
                            Ver documento PDF
                        </a>
                    @else
                        <span class="text-muted">Sin documento adjunto.</span>
                    @endif
                </div>
            </div>

            @if($solicitud->estado == 'rechazado')
                <div class="col-md-6">
                    <div class="info-box border-danger">
                        <div class="info-label text-danger">Motivo de rechazo</div>
                        <div>
                            {{ $solicitud->motivo_rechazo ?? 'No se registró motivo de rechazo.' }}
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <hr>

        <div class="mb-4">
            <h6 class="fw-bold" style="color:#1f3a56;">Empleados incluidos</h6>

            <div class="row">
                @foreach($solicitud->empleados as $emp)
                    <div class="col-md-6 mb-2">
                        <div class="border rounded p-2 bg-light">
                            {{ $emp->empleado->primer_nombre ?? '' }}
                            {{ $emp->empleado->segundo_nombre ?? '' }}
                            {{ $emp->empleado->primer_apellido ?? '' }}
                            {{ $emp->empleado->segundo_apellido ?? '' }}
                            <br>
                            <small class="text-muted">{{ $emp->dni_empleado }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if($solicitud->estado == 'pendiente')

            <form method="POST" action="{{ route('compensatorios.solicitudes.aprobar', $solicitud->id) }}">
                @csrf

                <div class="row align-items-end">
                    <div class="col-md-4 mb-3">
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

                    <div class="col-md-8 mb-3">
                        <div class="d-flex gap-2 justify-content-end">
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
                    </div>
                </div>
            </form>

        @else

            <div class="d-flex justify-content-end">
                <a href="{{ route('compensatorios.solicitudes.index') }}"
                   class="btn btn-secondary">
                    Volver
                </a>
            </div>

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