<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Login')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            overflow: hidden;
        }

        /* FONDO */
        .login-bg {
            position: relative;
            width: 100%;
            height: 100vh;
            background: url('/images/login-rrhh.png') no-repeat center;
            background-size: cover;
        }

        .login-bg::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(20, 40, 70, 0.25);
        }

        /* TARJETA */
        .login-card {
            position: absolute;
            top: 50%;
            left: 8%;
            transform: translateY(-50%);
            width: 420px;

            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(14px);

            padding: 35px;
            border-radius: 12px;

            box-shadow: 0 30px 60px rgba(0,0,0,0.25);

            animation: fadeInUp 0.6s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(-40%) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(-50%) scale(1);
            }
        }

        .logo {
            width: 130px;
            margin-bottom: 10px;
        }

        .login-title {
            font-weight: 600;
            color: #2c4a6b;
            font-size: 20px;
        }

        .login-text {
            font-size: 13px;
            color: #555;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px;
            font-size: 14px;
        }

        /* VALIDACIONES */
        .field-valid {
            border-color: #28a745 !important;
            box-shadow: 0 0 0 0.15rem rgba(40,167,69,.15);
        }

        .field-invalid {
            border-color: #dc3545 !important;
        }

        .error-text {
            font-size: 12px;
            color: #dc3545;
            margin-top: -8px;
            margin-bottom: 10px;
        }

        /* PASSWORD */
        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 14px;
            color: #2c4a6b;
        }

        /* BOTÓN */
        .btn-login {
            background: #2c4a6b;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 8px;
        }

        .btn-login:hover {
            background: #1f3a56;
        }

        .btn-login:disabled {
            background: #9ca3af;
        }

        .link {
            font-size: 13px;
            color: #2c4a6b;
            text-decoration: none;
        }

        .info-box {
            background: rgba(0,0,0,0.05);
            padding: 10px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
        }

        .info-content {
            display: none;
            font-size: 13px;
            margin-top: 10px;
        }
    </style>
</head>

<body>

<div class="login-bg">
    @yield('content')
</div>

<script>
function toggleInfo() {
    const content = document.getElementById('infoContent');
    content.style.display = content.style.display === 'block' ? 'none' : 'block';
}

function togglePassword() {
    const input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}

/* VALIDACIONES */
document.addEventListener("DOMContentLoaded", function(){

    const usuario = document.getElementById('usuario');
    const password = document.getElementById('password');
    const btn = document.getElementById('btnLogin');

    const errorUsuario = document.getElementById('errorUsuario');
    const errorPassword = document.getElementById('errorPassword');

    function validar(input, errorEl, min, mensaje) {

        if(input.value.trim() === ""){
            input.classList.add('field-invalid');
            input.classList.remove('field-valid');
            errorEl.textContent = "Campo obligatorio";
            return false;
        }

        if(input.value.length < min){
            input.classList.add('field-invalid');
            input.classList.remove('field-valid');
            errorEl.textContent = mensaje;
            return false;
        }

        input.classList.remove('field-invalid');
        input.classList.add('field-valid');
        errorEl.textContent = "";
        return true;
    }

    function validarForm(){
        const u = validar(usuario, errorUsuario, 3, "Mínimo 3 caracteres");
        const p = validar(password, errorPassword, 4, "Mínimo 4 caracteres");
        btn.disabled = !(u && p);
    }

    usuario.addEventListener('input', validarForm);
    password.addEventListener('input', validarForm);

    usuario.addEventListener('keypress', e => {
        if(e.key === ' ') e.preventDefault();
    });

});
</script>

</body>
</html>