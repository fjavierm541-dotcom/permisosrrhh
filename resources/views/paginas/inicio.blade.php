@extends('layouts.master')

@section('title', 'Inicio - Administrador')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Administrador</title>
    <!-- Bootstrap CSS desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Panel de Administrador</h1>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            <!-- Gestión de empleados -->
            <div class="col">
                <div class="card h-100 text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Gestión de Empleados</h5>
                        <p class="card-text">Crear, editar y administrar información de los empleados.</p>
                        <a href="#" class="btn btn-primary">Ir</a>
                    </div>
                </div>
            </div>

            <!-- Días acumulados -->
            <div class="col">
                <div class="card h-100 text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Días Acumulados</h5>
                        <p class="card-text">Visualizar y actualizar los días de permiso acumulados.</p>
                        <a href="#" class="btn btn-primary">Ir</a>
                    </div>
                </div>
            </div>

            <!-- Solicitudes de permiso -->
            <div class="col">
                <div class="card h-100 text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Solicitudes de Permiso</h5>
                        <p class="card-text">Revisar, aprobar o rechazar permisos solicitados por los empleados.</p>
                        <a href="#" class="btn btn-primary">Ir</a>
                    </div>
                </div>
            </div>

            <!-- Aprobación de permisos -->
            <div class="col">
                <div class="card h-100 text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Aprobación de Permisos</h5>
                        <p class="card-text">Gestionar aprobaciones pendientes y mantener control de permisos.</p>
                        <a href="#" class="btn btn-primary">Ir</a>
                    </div>
                </div>
            </div>

            <!-- Reportes -->
            <div class="col">
                <div class="card h-100 text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Reportes</h5>
                        <p class="card-text">Ver reportes generales o individuales sobre permisos y días acumulados.</p>
                        <a href="#" class="btn btn-primary">Ir</a>
                    </div>
                </div>
            </div>

            <!-- Configuración del sistema -->
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

    <!-- Bootstrap JS desde CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

@endsection
