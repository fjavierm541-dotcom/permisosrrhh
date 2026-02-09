@extends('layouts.master')

@section('title', 'Prueba')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista de Prueba Bootstrap</title>
    <!-- Bootstrap CSS desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Vista de Prueba Bootstrap</h1>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            <div class="col">
                <div class="card h-100 text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Opción 1</h5>
                        <p class="card-text">Este es un ejemplo de tarjeta con Bootstrap.</p>
                        <button class="btn btn-primary">Ir</button>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Opción 2</h5>
                        <p class="card-text">Otra tarjeta para probar el diseño.</p>
                        <button class="btn btn-success">Ir</button>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Opción 3</h5>
                        <p class="card-text">Se ve cómo se aplican los estilos de Bootstrap.</p>
                        <button class="btn btn-warning">Ir</button>
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