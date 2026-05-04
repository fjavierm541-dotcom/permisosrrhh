@extends('layouts.master')

@section('title', 'Módulo de Permisos')

@section('content')

<style>
    .card-option {
        display: block;
        text-decoration: none;
        color: inherit;
    }

    .option-card {
        position: relative;
        overflow: hidden;
        transition: all 0.28s ease;
        border: 1px solid transparent;
        min-height: 260px;
    }

    /* Línea dorada */
    .option-card::after {
        content: "";
        position: absolute;
        left: 50%;
        bottom: 22px;
        width: 0;
        height: 5px;
        background: #d4b06a;
        border-radius: 20px;
        transform: translateX(-50%);
        transition: width 0.28s ease;
    }

    .option-card img {
        height: 95px;
        transition: transform 0.28s ease;
    }

    .option-card h4 {
        color: #1f3a56;
        transition: color 0.28s ease;
    }

    /* HOVER GENERAL */
    .card-option:hover .option-card {
        transform: translateY(-7px);
        box-shadow: 0 25px 50px rgba(0,0,0,0.28);
        border-color: rgba(212, 176, 106, 0.65);
    }

    /* ICONO */
    .card-option:hover .option-card img {
        transform: scale(1.12) translateY(-6px);
    }

    /* TEXTO */
    .card-option:hover .option-card h4 {
        color: #d4b06a;
    }

    /* LINEA */
    .card-option:hover .option-card::after {
        width: 130px;
    }
</style>

<div class="container py-4">

    <div class="text-center mb-5">
        <h2 class="fw-bold text-white">Módulo de Permisos</h2>
        <p class="text-light">
            Selecciona el tipo de gestión que deseas realizar
        </p>
    </div>

    <div class="row justify-content-center g-4">

        <!-- PERMISOS LABORALES -->
        <div class="col-md-5">
            <a href="{{ route('permisos.index') }}" class="card-option">
                <div class="glass-card option-card text-center p-4 h-100">

                    <img src="{{ asset('icons/permisos.png') }}" class="mb-3">

                    <h4 class="fw-bold">Permisos laborales</h4>

                    <p class="text-muted">
                        Gestión de solicitudes para permisos de vacaciones, permisos personales, horas y otros.
                    </p>

                </div>
            </a>
        </div>

        <!-- DÍA NO LABORAL -->
        <div class="col-md-5">
            <a href="{{ route('compensatorios.solicitudes.index') }}" class="card-option">
                <div class="glass-card option-card text-center p-4 h-100">

                    <img src="{{ asset('icons/permisos.png') }}" class="mb-3">

                    <h4 class="fw-bold">Día no laboral</h4>

                    <p class="text-muted">
                        Solicitudes para trabajar en días inhábiles.
                    </p>

                </div>
            </a>
        </div>

    </div>

</div>

@endsection