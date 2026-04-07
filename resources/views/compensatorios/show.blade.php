@extends('layouts.master')

@section('title', 'Detalle Solicitud')

@section('content')

<div class="glass-card p-0 overflow-hidden">

    <!-- HEADER -->
    <div class="px-4 py-3" style="background:#2d4f73; color:white;">
        <h5 class="mb-0">
            Solicitud del departamento: 
            {{ $solicitud->departamento->nombre ?? '—' }}
        </h5>
    </div>

    <div class="p-4">

        <!-- INFO -->
        <p>
    <strong>Fecha solicitada a laborar:</strong><br>
    {{ \Carbon\Carbon::parse($solicitud->fecha_trabajada)
        ->locale('es')
        ->translatedFormat('l j \\d\\e F \\d\\e\\l Y') }}
</p>

        <p>
            <strong>Estado:</strong>
            <span class="badge 
                {{ $solicitud->estado == 'pendiente' ? 'bg-warning' : '' }}
                {{ $solicitud->estado == 'aprobado' ? 'bg-success' : '' }}
                {{ $solicitud->estado == 'rechazado' ? 'bg-danger' : '' }}">
                {{ ucfirst($solicitud->estado) }}
            </span>
        </p>

        <hr>

        <!-- EMPLEADOS -->
        <h6>Empleados incluidos</h6>

        <ul class="mb-3">
           @foreach($solicitud->empleados as $emp)
    <li>
        {{ $emp->empleado->primer_nombre ?? '' }}
        {{ $emp->empleado->primer_apellido ?? '' }}
        ({{ $emp->dni_empleado }})
    </li>
@endforeach
        </ul>

        @if($solicitud->estado == 'pendiente')

        <!-- FORM -->
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

            <!-- BOTONES -->
            <div class="d-flex gap-2">

                <button type="submit" class="btn btn-success">
                    Aprobar
                </button>
        </form>

        <form method="POST" action="{{ route('compensatorios.solicitudes.rechazar', $solicitud->id) }}">
            @csrf
                <button type="submit" class="btn btn-danger">
                    Rechazar
                </button>
        </form>

            <a href="{{ route('compensatorios.solicitudes.index') }}"
               class="btn btn-secondary">
                Volver
            </a>

            </div>

        @endif

    </div>
</div>

@endsection