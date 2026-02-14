@extends('layouts.master')

@section('title', 'Inicio')

@section('content')

<style>
    .dashboard-container {
        background: rgba(255,255,255,0.95);
        border-radius: 20px;
        box-shadow: 0 20px 45px rgba(0,0,0,0.25);
        padding: 40px;
    }

    .dashboard-title {
        font-weight: 700;
        color: #1f3a56;
        letter-spacing: 1px;
    }

    .dashboard-subtitle {
        color: #6c757d;
        font-size: 15px;
    }

    .dashboard-card {
        background: linear-gradient(135deg, #ffffff, #f1f4f8);
        border-radius: 16px;
        border: none;
        transition: all 0.3s ease;
        height: 100%;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.15);
    }

    .dashboard-icon {
        font-size: 28px;
        color: #2d4f73;
        margin-bottom: 15px;
    }

    .btn-dashboard {
        background-color: #d4b06a;
        border: none;
        color: #1f3a56;
        font-weight: 600;
    }

    .btn-dashboard:hover {
        background-color: #c39a4f;
        color: #1f3a56;
    }
</style>

<div class="container py-5">

    <div class="dashboard-container">

        <div class="text-center mb-5">
            <h2 class="dashboard-title">Panel de Administración</h2>
            <p class="dashboard-subtitle">
                Gestiona empleados, permisos y configuraciones del sistema
            </p>
        </div>

        <div class="row g-4">

            <!-- Empleados -->
            <div class="col-md-4">
                <div class="card dashboard-card text-center p-4">
                    <div class="dashboard-icon">👥</div>
                    <h5>Empleados</h5>
                    <p class="text-muted small">
                        Visualizar y administrar información del personal.
                    </p>
                    <a href="{{ route('empleados.index') }}" class="btn btn-dashboard btn-sm">
                        Ingresar
                    </a>
                </div>
            </div>

            <!-- Permisos -->
            <div class="col-md-4">
                <div class="card dashboard-card text-center p-4">
                    <div class="dashboard-icon">📅</div>
                    <h5>Permisos</h5>
                    <p class="text-muted small">
                        Crear, revisar y aprobar solicitudes.
                    </p>
                    <a href="{{ route('permisos.index') }}" class="btn btn-dashboard btn-sm">
                        Ingresar
                    </a>
                </div>
            </div>

            <!-- Carga Histórica -->
            <div class="col-md-4">
                <div class="card dashboard-card text-center p-4">
                    <div class="dashboard-icon">🗂</div>
                    <h5>Períodos de Vacaciones</h5>
                    <p class="text-muted small">
                        Administrar períodos históricos y vencimientos.
                    </p>
                    <a href="{{ route('periodos.create') }}" class="btn btn-dashboard btn-sm">
                        Ingresar
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
