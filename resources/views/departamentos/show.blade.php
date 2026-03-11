@extends('layouts.master')

@section('title','Departamento')

@section('content')

<div class="glass-card">

    <!-- HEADER -->
    <div class="p-3 text-white"
        style="background:#2f4f6f;border-top-left-radius:18px;border-top-right-radius:18px;">

        <div class="d-flex justify-content-between">

            <h5 class="mb-0">
                Departamento {{ $departamento->codigo }}
            </h5>

            <div>

                <a href="{{ route('departamentos.jefe',$departamento->id) }}"
                class="btn btn-primary-custom btn-sm">
                Cambiar jefe
                </a>    

                <a href="{{ route('departamentos.asignar',$departamento->id) }}"
                    class="btn btn-primary-custom btn-sm">
                    Agregar empleados
                </a>

                <a href="{{ route('departamentos.index') }}"
                    class="btn btn-secondary btn-sm">
                    Volver
                </a>

    

            </div>

        </div>

    </div>


    <!-- INFO -->
    <div class="p-3">

        <p>
            <strong>Nombre:</strong>
            {{ $departamento->nombre }}
        </p>

        <p>
            <strong>Jefe de departamento:</strong>

            @if($departamento->jefe_dni)
                {{ $departamento->jefe->primer_nombre ?? '' }}
                {{ $departamento->jefe->primer_apellido ?? '' }}
            @else
                <span class="text-muted">No asignado</span>
            @endif
        </p>

        <hr>

        <h6>
            Empleados asignados
        </h6>

    </div>


    <!-- TABLA EMPLEADOS -->
    <div class="table-responsive">

        <table class="table align-middle">

            <thead style="background:#3a5a7c;color:white">

                <tr>

                    <th>DNI</th>

                    <th>Nombre</th>

                    <th>Puesto</th>

                </tr>

            </thead>

            <tbody>

                @forelse($departamento->empleados as $emp)

                    <tr>

                        <td>
                            {{ $emp->DNI }}
                        </td>

                        <td>
                            {{ $emp->primer_nombre }} {{ $emp->segundo_nombre }}
                            {{ $emp->primer_apellido }} {{ $emp->segundo_apellido }}
                        </td>

                        <td>
                            {{ $emp->puesto }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="3"
                            class="text-center text-muted">

                            Este departamento aún no tiene empleados asignados.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection