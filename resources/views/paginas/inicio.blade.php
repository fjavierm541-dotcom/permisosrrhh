@extends('layouts.master')

@section('title', 'Inicio')

@section('content')

<style>
/* CONTENEDOR PRINCIPAL (GLASS REAL) */
.dashboard-container {
    background: rgba(255,255,255,0.75);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    border-radius: 20px;
    box-shadow: 0 20px 45px rgba(0,0,0,0.25);
    padding: 40px;
    border: 1px solid rgba(255,255,255,0.3);
}

/* TÍTULOS */
.dashboard-title {
    font-weight: 700;
    color: #1f3a56;
    letter-spacing: 1px;
}

.dashboard-subtitle {
    color: #6c757d;
    font-size: 15px;
}

/* TARJETAS */
.dashboard-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(241,244,248,0.85));
    backdrop-filter: blur(6px);
    border-radius: 16px;
    border: 1px solid rgba(255,255,255,0.4);
    transition: all 0.3s ease;
    height: 100%;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

/* EFECTO HOVER PRINCIPAL */
.dashboard-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 
        0 20px 40px rgba(0,0,0,0.2),
        0 0 0 1px rgba(212,176,106,0.4);
    border: 1px solid rgba(212,176,106,0.6);
}

/* BRILLO SUTIL (GLASS PREMIUM) */
.dashboard-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        120deg,
        transparent,
        rgba(255,255,255,0.5),
        transparent
    );
    transition: 0.6s;
}

.dashboard-card:hover::before {
    left: 100%;
}

/* LÍNEA DECORATIVA */
.dashboard-card::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 50px;
    height: 4px;
    background: #d4b06a;
    border-radius: 10px;
    opacity: 0;
    transition: 0.3s;
}

.dashboard-card:hover::after {
    opacity: 1;
    width: 90px;
}

/* ICONOS */
.icono-img {
    width: 90px;
    height: 90px;
    object-fit: contain;
    margin-bottom: 15px;
    transition: transform 0.3s ease;
    filter: drop-shadow(0 10px 12px rgba(0,0,0,0.15));
}

/* ICONO HOVER */
.dashboard-card:hover .icono-img {
    transform: translateY(-6px) scale(1.07);
    filter: drop-shadow(0 20px 25px rgba(0,0,0,0.25));
}

/* CONTENEDOR ICONO */
.dashboard-icon {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 10px;
}

/* LINKS */
.dashboard-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.dashboard-link:hover {
    text-decoration: none;
    color: inherit;
}

/* TEXTO HOVER */
.dashboard-card:hover h5 {
    color: #d4b06a;
    transition: 0.3s;
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
    <a href="{{ route('empleados.index') }}" class="dashboard-link">
        <div class="card dashboard-card text-center p-4">
            <div class="dashboard-icon">
                <img src="{{ asset('icons/empleado.png') }}" class="icono-img">
            </div>
            <h5>Empleados</h5>
            <p class="text-muted small">
                Administrar la información del personal.
            </p>
        </div>
    </a>
</div>

            <!-- Permisos -->
            <div class="col-md-4">
    <a href="{{ route('permisos.index') }}" class="dashboard-link">
        <div class="card dashboard-card text-center p-4">
            <div class="dashboard-icon">
                <img src="{{ asset('icons/permisos.png') }}" class="icono-img">
            </div>
            <h5>Permisos</h5>
            <p class="text-muted small">
                Crear, revisar y aprobar solicitudes.
            </p>
        </div>
    </a>
</div>

            <!-- Carga Histórica -->
            <div class="col-md-4">
    <a href="{{ route('periodos.create') }}" class="dashboard-link">
        <div class="card dashboard-card text-center p-4">
            <div class="dashboard-icon">
                <img src="{{ asset('icons/registro.png') }}" class="icono-img">
            </div>
            <h5>Registro histórico de vacaciones</h5>
            <p class="text-muted small">
                Carga de historial de vacaciones de empleados.
            </p>
        </div>
    </a>
</div>


            <div class="row g-4">

            <!-- DEPTOS -->
            <div class="col-md-4">
    <a href="{{ route('departamentos.index') }}" class="dashboard-link">
        <div class="card dashboard-card text-center p-4">
            <div class="dashboard-icon">
                <img src="{{ asset('icons/deptos.png') }}" class="icono-img">
            </div>
            <h5>Departamentos</h5>
            <p class="text-muted small">
                Visualizar la información de los departamentos.
            </p>
        </div>
    </a>
</div>

            <!-- CALENDARIO -->
            <div class="col-md-4">
    <a href="{{ route('calendario.index') }}" class="dashboard-link">
        <div class="card dashboard-card text-center p-4">
            <div class="dashboard-icon">
                <img src="{{ asset('icons/calendario.png') }}" class="icono-img">
            </div>
            <h5>Calendario</h5>
            <p class="text-muted small">
                Ver y agregar feriados nacionales y locales.
            </p>
        </div>
    </a>
</div>

            <!-- AJUSTES -->
            <div class="col-md-4">
    <a href="{{ route('periodos.create') }}" class="dashboard-link">
        <div class="card dashboard-card text-center p-4">
            <div class="dashboard-icon">
                <img src="{{ asset('icons/ajustes.png') }}" class="icono-img">
            </div>
            <h5>Ajustes</h5>
            <p class="text-muted small">
                Configuración general del sistema.
            </p>
        </div>
    </a>
</div>

            

        </div>

    </div>

</div>




@endsection
