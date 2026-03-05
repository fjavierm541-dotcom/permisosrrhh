@extends('layouts.master')

@section('title', 'Lista de empleados')

@section('content')
    


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

         <!-- HEADER -->
        <div class="card-header-custom p-4 d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Listado de Empleados</h4>

            <div class="d-flex gap-2">

                <!-- BOTÓN GENERAR VACACIONES -->
                <form method="POST" action="{{ route('vacaciones.generar') }}">
                    @csrf
                    <button class="btn btn-warning btn-sm">
                        Generar Vacaciones Año Actual
                    </button>
                </form>
                <!-- BOTÓN CREAER -->
                <a href="{{ route('empleados.create') }}"
                class="btn btn-primary-custom btn-sm">
                Registrar Empleado
                </a>

                <!-- BOTÓN ATRÁS -->
                <a href="{{ url()->previous() }}" class="btn btn-primary-custom btn-sm">
                    Atrás
                </a>
            </div>
            
        </div>

        
        <div class="p-4">

            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre del empleado</th>
                            <th>DNI</th>
                            <th>Días disponibles</th>
                            <th>Ver por riesgo de vencimiento
                                <div class="mb-3 text-center">

    <a href="{{ route('empleados.index') }}"
       class="btn btn-outline-dark btn-sm">
        Ver todos
    </a>

    <a href="{{ route('empleados.index', ['estado' => 'rojo']) }}"
       class="btn btn-danger btn-sm">
        🔴 Alto
    </a>

    <a href="{{ route('empleados.index', ['estado' => 'amarillo']) }}"
       class="btn btn-warning btn-sm">
        🟡 Medio
    </a>

    <a href="{{ route('empleados.index', ['estado' => 'verde']) }}"
       class="btn btn-success btn-sm">
        🟢 Bajo
    </a>

</div>
                            </th>
                            <th>Historial</th>
                            <th>Registro</th>
                        </tr>
                        
                    </thead>

                    <tbody>
                        @foreach($empleados as $index => $empleado)
                            <tr>

                            <td>
                        {{ ($empleados->currentPage() - 1) * $empleados->perPage() + $index + 1 }}
                            </td>


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
                                    Ver
                                </a>
                            </td>
                             <td>
                                <a href="{{ route('empleados.verRegistro', $empleado->DNI) }}"
                                 class="btn btn-sm btn-dark">
                                Ver 
                                </a>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>

                </table>

                 <div class="mt-3 d-flex justify-content-center">
                {{ $empleados->links() }}
            </div>
            </div>

        </div>
    </div>

</div>


@endsection