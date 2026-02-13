<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Empleados</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #1f3a56, #2d4f73);
            min-height: 100vh;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 18px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
        }

        .card-header-custom {
            background-color: #274769;
            color: white;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
        }

        .badge-semaforo {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            display: inline-block;
        }

        .verde { background-color: #28a745; }
        .amarillo { background-color: #ffc107; }
        .rojo { background-color: #dc3545; }

        table th {
            background-color: #2d4f73 !important;
            color: white;
        }
    </style>
</head>

<body>

<div class="container py-5">

    <div class="glass-card">

        <div class="card-header-custom p-4">
            <h4 class="mb-0">Listado de Empleados</h4>
        </div>

        <div class="p-4">

            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">

                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>DNI</th>
                            <th>Días disponibles</th>
                            <th>Semáforo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($empleados as $empleado)
                        <tr>

                            <td>
                                {{ $empleado->primer_nombre }}
                                {{ $empleado->primer_apellido }}
                            </td>

                            <td>{{ $empleado->DNI }}</td>

                            <td>
                                {{ $empleado->dias_disponibles }} días
                                y
                                {{ $empleado->horas_disponibles }} horas
                            </td>

                            <td>
                                <span class="badge-semaforo {{ $empleado->semaforo }}"></span>
                            </td>

                            <td>
                                <a href="{{ route('empleados.show', $empleado->DNI) }}"
                                   class="btn btn-sm btn-dark">
                                    Más
                                </a>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

</body>
</html>
