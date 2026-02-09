@extends('layouts.master')

@section('title', 'Inicio - Administrador')

@section('content')
<div class="container">
    <h2 class="mb-4">Panel de Administrador</h2>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        <!-- Gestión de empleados -->
        <div class="col">
            <div class="card h-100 text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Gestión de Empleados</h5>
                    <p class="card-text">Crear, editar y administrar la información de los empleados.</p>
                    <a href="#" class="btn btn-primary">Ir</a>
                </div>
            </div>
        </div>

        <!-- Días acumulados -->
        <div class="col">
            <div class="card h-100 text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Días Acumulados</h5>
                    <p class="card-text">Visualizar y actualizar los días de permiso acumulados de los empleados.</p>
                    <a href="#" class="btn btn-primary">Ir</a>
                </div>
            </div>
        </div>

        <!-- Solicitudes de permiso -->
        <div class="col">
            <div class="card h-100 text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Solicitudes de Permiso</h5>
                    <p class="card-text">Revisar, aprobar o rechazar los permisos solicitados por los empleados.</p>
                    <a href="#" class="btn btn-primary">Ir</a>
                </div>
            </div>
        </div>

        <!-- Aprobación de permisos -->
        <div class="col">
            <div class="card h-100 text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Aprobación de Permisos</h5>
                    <p class="card-text">Gestionar aprobaciones pendientes y mantener el control de permisos.</p>
                    <a href="#" class="btn btn-primary">Ir</a>
                </div>
            </div>
        </div>

        <!-- Reportes -->
        <div class="col">
            <div class="card h-100 text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Reportes</h5>
                    <p class="card-text">Ver reportes generales o individuales sobre los permisos y días acumulados.</p>
                    <a href="#" class="btn btn-primary">Ir</a>
                </div>
            </div>
        </div>

        <!-- Configuración de sistema -->
        <div class="col">
            <div class="card h-100 text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Configuración del Sistema</h5>
                    <p class="card-text">Modificar parámetros generales, usuarios y roles del sistema.</p>
                    <a href="#" class="btn btn-primary">Ir</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
