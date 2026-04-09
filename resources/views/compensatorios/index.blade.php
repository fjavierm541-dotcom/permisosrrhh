@extends('layouts.master')

@section('title', 'Solicitudes de Compensatorios')

@section('content')

<div class="glass-card p-0 overflow-hidden">

    <!-- HEADER AZUL -->
    <div class="d-flex justify-content-between align-items-center px-4 py-3"
         style="background:#2d4f73; color:white;">

        <h5 class="mb-0">Gestión de Compensatorios</h5>

        <a href="{{ route('compensatorios.solicitudes.create') }}"
           class="btn btn-warning btn-sm">
            + Nueva Solicitud
        </a>
    </div>

    <div class="p-4">

        <table class="table align-middle">
            <thead style="background:#2d4f73; color:white;">
                <tr>
                    <th># solicitud</th>
                    <th>Departamento</th>
                    <th>Fecha de solicitud</th>
                    <th>Empleados incluidos</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @foreach($solicitudes as $sol)
                    <tr>
                        <td>{{ $sol->id }}</td>

                        <td>
                            {{ $sol->departamento->nombre ?? '—' }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($sol->fecha_trabajada)->format('d/m/Y') }}
                        </td>

                        <td>{{ $sol->empleados->count() }}</td>

                        <td>
                            <span class="badge 
                                {{ $sol->estado == 'pendiente' ? 'bg-warning' : '' }}
                                {{ $sol->estado == 'aprobado' ? 'bg-success' : '' }}
                                {{ $sol->estado == 'rechazado' ? 'bg-danger' : '' }}">
                                {{ ucfirst($sol->estado) }}
                            </span>
                        </td>

                        <td>
                            <a href="{{ route('compensatorios.solicitudes.show', $sol->id) }}"
                               class="btn btn-sm btn-primary-custom">
                                Ver
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

@endsection