@extends('layouts.auth')

@section('title', 'Login')

@section('content')

<div class="login-card">

    <img src="/images/logo.png" class="logo" alt="Logo">

    <div class="login-title mb-2">
        Sistema de Permisos RRHH
    </div>

    <p class="login-text">
        Accede de forma segura para gestionar empleados y permisos.
    </p>

    <p class="login-text">
        Tienes <strong>5 intentos</strong> antes de bloqueo.
    </p>

    <form id="loginForm" novalidate>

        <input type="text" id="usuario" class="form-control mb-1" placeholder="Usuario">
        <div id="errorUsuario" class="error-text"></div>

        <div class="password-wrapper mb-1">
            <input type="password" id="password" class="form-control" placeholder="Contraseña">
            <span class="toggle-password" onclick="togglePassword()">👁</span>
        </div>
        <div id="errorPassword" class="error-text"></div>

        <button id="btnLogin" class="btn-login mt-2" disabled>
            Iniciar sesión
        </button>

    </form>

    <a href="#" class="link d-block mt-2">¿Olvidaste tu contraseña?</a>

    <div class="info-box" onclick="toggleInfo()">
        Conoce más sobre el sistema +
    </div>

    <div id="infoContent" class="info-content">
        Sistema para gestionar empleados, permisos y calendarios laborales.
    </div>

</div>

@endsection