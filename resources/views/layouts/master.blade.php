<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Permisos')</title>
    <link href="{{ asset('build/assets/app.css') }}" rel="stylesheet">
<script src="{{ asset('build/assets/app.js') }}"></script>

    
</head>
<body>
    <header class="bg-primary text-white p-3 mb-4">
        <h1>Sistema de Permisos HRR</h1>
    </header>

    <main class="container">
        @yield('content')
    </main>

    <footer class="bg-light text-center p-3 mt-4">
        &copy; 2026 Municipalidad
    </footer>

    <script src="{{ asset('build/assets/app.js') }}"></script>
</body>
</html>
