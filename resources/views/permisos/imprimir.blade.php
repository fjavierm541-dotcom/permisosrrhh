@extends('layouts.master')

@section('title','Imprimir permiso')

@section('content')


<style>@media print {

    .navbar,
    .btn {
        display: none;
    }

    body {
        background: white;
    }

}
</style>

<div class="glass-card p-4">

    <div class="text-center mb-4">

        <h5 class="fw-bold">
            PASE DE SALIDA EDIFICIO MUNICIPAL
        </h5>

        <small class="text-muted">
            Usar este formato para salidas del edificio municipal
            por espacio no mayor a las 8 horas
        </small>

    </div>


    <table class="table table-bordered">

        <tr>
            <th width="30%">DE:</th>
            <td>
                {{ $empleado->primer_nombre }}
                {{ $empleado->primer_apellido }}
            </td>
        </tr>

        <tr>
            <th>DEPARTAMENTO ASIGNADO:</th>
            <td>
                {{ $empleado->departamentoFuncional->nombre ?? '' }}
            </td>
        </tr>

        <tr>
            <th>No. DE EMPLEADO:</th>
            <td>
                {{ $empleado->codigo }}
            </td>
        </tr>

        <tr>
            <th>FECHA:</th>
            <td>
                {{ $permiso->fecha }}
            </td>
        </tr>

        <tr>
            <th>ASUNTO:</th>
            <td>
                SOLICITUD DE PERMISO
            </td>
        </tr>

        <tr>
            <th>RAZÓN:</th>
            <td>
                {{ $permiso->razon }}
            </td>
        </tr>

        <tr>
            <th>HORA DE SALIDA:</th>
            <td>
                {{ $permiso->hora_salida }}
            </td>
        </tr>

        <tr>
            <th>HORA DE ENTRADA:</th>
            <td>
                {{ $permiso->hora_regreso }}
            </td>
        </tr>

        <tr>
            <th>TIEMPO TOMADO:</th>
            <td>
                {{ $permiso->tiempo }}
            </td>
        </tr>

    </table>


    <div class="row text-center mt-5">

        <div class="col-md-4">

            ___________________________

            <br>

            {{ $empleado->primer_nombre }}
            {{ $empleado->primer_apellido }}

            <br>

            Empleado

        </div>


        <div class="col-md-4">

            ___________________________

            <br>

            {{ $jefeDepto->primer_nombre ?? '' }}
            {{ $jefeDepto->primer_apellido ?? '' }}

            <br>

            Jefe de departamento

        </div>


        <div class="col-md-4">

            ___________________________

            <br>

            {{ $jefeRRHH->primer_nombre ?? '' }}
            {{ $jefeRRHH->primer_apellido ?? '' }}

            <br>

            Jefe Recursos Humanos

        </div>

    </div>


    <div class="text-end mt-4">

        <button
            onclick="window.print()"
            class="btn btn-primary-custom">

            Imprimir

        </button>

    </div>

</div>

@if(session('permiso_imprimir'))

<script>

window.open(
    "{{ route('permisos.imprimir', session('permiso_imprimir')) }}",
    "_blank"
);

</script>

@endif

@endsection