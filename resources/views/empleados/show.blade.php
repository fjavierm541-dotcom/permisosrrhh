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
   Imprimir PDF
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
        <span class="text-muted">Pendiente de implementar</span>
    </div>

</div>


    <!-- RESUMEN DISPONIBLE -->
    <div class="alert alert-info">
        <strong>Días disponibles actuales:</strong>
        {{ $totalDiasDisponibles }} días
    </div>

</div>



<!-- PERÍODOS ACTIVOS -->
<div class="glass-card p-4 mb-4">

    <h5 class="mb-1">Períodos Activos</h5>
<small class="text-muted">Año(s) que todavía tienen día(s) libre.</small>

    

    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead>
                <tr>
                    <th>Año</th>
                    <th>Días otorgados</th>
                    <th>Días usados</th>
                    <th>Días restantes</th>
                    <th>Vencimiento</th>
                </tr>
            </thead>
            <tbody>
                @forelse($periodosActivos as $periodo)
                    <tr>
                        <td>{{ $periodo->anio_laboral }}</td>
                        <td>{{ $periodo->dias_otorgados }}</td>
                        <td>{{ $periodo->dias_usados }}</td>
                        <td>{{ $periodo->dias_otorgados - $periodo->dias_usados }}</td>
                        <td>{{ \Carbon\Carbon::parse($periodo->extension_hasta ?? $periodo->fecha_vencimiento)->format('d-m-y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No hay períodos activos.</td>
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
                    <th>Días otorgados</th>
                    <th>Días usados</th>
                    <th>Vencimiento</th>
                </tr>
            </thead>
            <tbody>
                @forelse($periodosVencidos as $periodo)
                    <tr class="table-danger">
                        <td>{{ $periodo->anio_laboral }}</td>
                        <td>{{ $periodo->dias_otorgados }}</td>
                        <td>{{ $periodo->dias_usados }}</td>
                        <td>{{ \Carbon\Carbon::parse($periodo->fecha_vencimiento)->format('d-m-y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No hay períodos vencidos.</td>
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
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th>Días</th>
                    <th>Horas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movimientos as $movimiento)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($movimiento->created_at)->format('d-m-y H:i') }}</td>
                        <td>{{ $movimiento->tipo_movimiento }}</td>
                        <td>{{ $movimiento->descripcion }}</td>
                        <td>{{ $movimiento->dias_afectados ?? '-' }}</td>
                        <td>{{ $movimiento->horas_afectadas ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No hay movimientos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
