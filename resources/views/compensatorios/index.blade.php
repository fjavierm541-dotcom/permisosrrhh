@extends('layouts.master')

@section('title', 'Solicitudes de Compensatorios')

@section('content')

<style>
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

    .btn-blue {
        background-color: #1f3a56;
        border: none;
        color: white;
        font-weight: 600;
    }

    .btn-blue:hover {
        background-color: #162a40;
        color: white;
    }

    table th {
        background-color: #2d4f73 !important;
        color: white;
    }
</style>

<div class="glass-card p-0 overflow-hidden">

    <div class="d-flex justify-content-between align-items-center px-4 py-3"
         style="background:#2d4f73; color:white;">

        <h5 class="mb-0">Gestión de Compensatorios</h5>

        <div class="d-flex gap-2">
            <a href="{{ route('permisos.menu') }}" class="btn btn-outline-light btn-sm">
                Volver
            </a>

            <a href="{{ route('compensatorios.solicitudes.create') }}" class="btn btn-gold btn-sm">
                + Nueva Solicitud
            </a>
        </div>
    </div>

    <div class="p-4">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th># Solicitud</th>
                        <th>Departamento</th>
                        <th>Fecha trabajada</th>
                        <th>Empleados incluidos</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($solicitudes as $sol)
                        <tr>
                            <td>{{ $sol->id }}</td>
                            <td>{{ $sol->departamento->nombre ?? '—' }}</td>
                            <td>{{ \Carbon\Carbon::parse($sol->fecha_trabajada)->format('d-m-Y') }}</td>
                            <td>{{ $sol->empleados->count() }}</td>

                            <td>
                                @if($sol->estado == 'pendiente')
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                @elseif($sol->estado == 'aprobado')
                                    <span class="badge bg-success">Aprobado</span>
                                @else
                                    <span class="badge bg-danger">Rechazado</span>
                                @endif
                            </td>

                            <td class="text-end">
                                <a href="{{ route('compensatorios.solicitudes.show', $sol->id) }}"
                                   class="btn btn-blue btn-sm">
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                No hay solicitudes registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            {{ $solicitudes->links() }}
        </div>

    </div>
</div>

@endsection