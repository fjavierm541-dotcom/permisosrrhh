<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard RRHH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #1f3a56, #2d4f73);
            min-height: 100vh;
        }

        .glass-card {
            background: rgba(255,255,255,0.95);
            border-radius: 18px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
        }

        .card-header-custom {
            background-color: #274769;
            color: white;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
        }

        .big-number {
            font-size: 3rem;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container py-5">

    <div class="glass-card">

        <div class="card-header-custom p-4">
            <h4 class="mb-0">Panel General de Vacaciones</h4>
        </div>

        <div class="p-5">

            <div class="row text-center">

                <div class="col-md-4 mb-4">
                    <div class="card border-danger shadow-sm">
                        <div class="card-body">
                            <h5 class="text-danger">🔴 Riesgo Alto</h5>
                            <div class="big-number text-danger">
                                {{ $rojos }}
                            </div>
                            <p>Vacaciones por vencer</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card border-warning shadow-sm">
                        <div class="card-body">
                            <h5 class="text-warning">🟡 Riesgo Medio</h5>
                            <div class="big-number text-warning">
                                {{ $amarillos }}
                            </div>
                            <p>Próximas a vencer</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card border-success shadow-sm">
                        <div class="card-body">
                            <h5 class="text-success">🟢 Bajo Riesgo</h5>
                            <div class="big-number text-success">
                                {{ $verdes }}
                            </div>
                            <p>Sin riesgo inmediato</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="text-center mt-4">
                <a href="{{ route('empleados.index') }}" class="btn btn-dark">
                    Ver listado completo de empleados
                </a>
            </div>

        </div>
    </div>

</div>

</body>
</html>
