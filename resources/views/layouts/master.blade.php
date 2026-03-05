<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sistema RRHH')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #1f3a56, #2d4f73);
            min-height: 100vh;
            padding-top: 75px; /* 👈 Espacio para navbar fijo */
        }

        .navbar-custom {
            background: linear-gradient(90deg, #1f3a56, #2d4f73);
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: white;
            font-weight: 500;
        }

        .navbar-custom .nav-link:hover {
            color: #d4b06a;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 18px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
        }

        .btn-primary-custom {
            background-color: #1f3a56;
            border: none;
            color: white;
        }

        .btn-primary-custom:hover {
            background-color: #162a40;
        }



        /* ---------- MICRO FEEDBACK INPUTS ---------- */

input, select, textarea {
    transition: border-color 0.25s ease, box-shadow 0.25s ease;
}

.field-valid {
    border-color: #28a745 !important;
    box-shadow: 0 0 0 0.15rem rgba(40,167,69,.15);
}

.field-invalid {
    border-color: #dc3545 !important;
}

/* ---------- TRANSICIÓN DE PÁGINA ---------- */

.page-enter {
    animation: pageFade 0.25s ease;
}

@keyframes pageFade {
    from {
        opacity: 0;
        transform: translateY(8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* LOADER DE PÁGINA */

#page-loader{
    position: fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:#1f3a56;
    display:flex;
    justify-content:center;
    align-items:center;
    z-index:9999;
}

    </style>
</head>

<body>
    
<div id="page-loader">
    <div class="spinner-border text-light" role="status"></div>
</div>

<!-- NAVBAR SUPERIOR FIJO -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top shadow">
    <div class="container-fluid px-4">

        <!-- Nombre del sistema -->
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            Sistema de Permisos RRHH
        </a>

        <!-- Botón responsive -->
        <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <!-- Links izquierda -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('paginas.inicio') ? 'active fw-bold text-warning' : '' }}"
   href="{{ route('paginas.inicio') }}">
   Inicio
</a>

                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('empleados.*') ? 'active fw-bold text-warning' : '' }}"
   href="{{ route('empleados.index') }}">
   Empleados
</a>

                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('permisos.*') ? 'active fw-bold text-warning' : '' }}"
   href="{{ route('permisos.index') }}">
   Permisos
</a>

                </li>
            </ul>

            <!-- Usuario logueado -->
            <ul class="navbar-nav">

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        {{ auth()->user()->name ?? 'Usuario' }}
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">

    <li>
        <a class="dropdown-item" href="{{ route('dashboard') }}">
            Monitoreo de Días Disponibles
        </a>
    </li>

    <li>
        <a class="dropdown-item" href="#">
            Ajustes
        </a>
    </li>

    <li><hr class="dropdown-divider"></li>

    <li>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item text-danger">
                Cerrar sesión
            </button>
        </form>
    </li>

</ul>

                </li>

            </ul>
        </div>
    </div>
</nav>

<!-- CONTENIDO DINÁMICO -->
<div class="container py-4 page-enter">
    @yield('content')
</div>

<!-- Bootstrap JS -->
<script>

document.addEventListener("input", function(e){

    if(e.target.matches("input, select, textarea")){

        const field = e.target

        if(field.value.trim().length > 0){

            field.classList.remove("field-invalid")
            field.classList.add("field-valid")

        }else{

            field.classList.remove("field-valid")
            field.classList.add("field-invalid")

        }

    }

})

</script>
<!-- LOADER -->
<script>

window.addEventListener("load", function(){

    const loader = document.getElementById("page-loader")

    loader.style.opacity = "0"

    setTimeout(function(){
        loader.style.display = "none"
    },300)

})

</script>
</body>
</html>
