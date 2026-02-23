@extends('layouts.master')

@section('title', 'Ver Registro')

@section('content')

<div class="glass-card p-4">

    <h4 class="fw-bold text-center mb-4">
        REGISTRO OFICIAL DE PERSONAL
    </h4>

    <table class="table table-bordered">
        <tr>
            <th>Código</th>
            <td>{{ $empleado->codigo }}</td>
            <th>DNI</th>
            <td>{{ $empleado->DNI }}</td>
        </tr>
        <tr>
            <th>Primer Nombre</th>
            <td>{{ $empleado->primer_nombre }}</td>
            <th>Segundo Nombre</th>
            <td>{{ $empleado->segundo_nombre }}</td>
        </tr>
        <tr>
            <th>Primer Apellido</th>
            <td>{{ $empleado->primer_apellido }}</td>
            <th>Segundo Apellido</th>
            <td>{{ $empleado->segundo_apellido }}</td>
        </tr>
        <tr>
            <th>Estado Civil</th>
            <td>{{ $empleado->estado_civil }}</td>
            <th>Tipo de Sangre</th>
            <td>{{ $empleado->tipo_sangre }}</td>
        </tr>
        <tr>
            <th>Dirección</th>
            <td colspan="3">{{ $empleado->direccion_domicilio }}</td>
        </tr>
        <tr>
            <th>Teléfono Celular</th>
            <td>{{ $empleado->telefono_celular }}</td>
            <th>Teléfono Fijo</th>
            <td>{{ $empleado->telefono_fijo }}</td>
        </tr>
    </table>

    <h5 class="fw-bold mt-4">Información Laboral</h5>

    <table class="table table-bordered">
        <tr>
            <th>Puesto</th>
            <td>{{ $empleado->puesto }}</td>
            <th>Tipo</th>
            <td>{{ $empleado->tipo }}</td>
        </tr>
        <tr>
            <th>Fecha Nombramiento</th>
            <td>{{ $empleado->fecha_nombramiento }}</td>
            <th>Salario Inicial</th>
            <td>L. {{ number_format($empleado->salario_inicial, 2) }}</td>
        </tr>
    </table>

    <h5 class="fw-bold mt-4">Beneficiarios</h5>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Parentezco</th>
                <th>%</th>
                <th>DNI</th>
            </tr>
        </thead>
        <tbody>
        @for ($i = 1; $i <= 7; $i++)
            @php
                $nombre = "nombre_beneficiario".$i;
                $porcentaje = "porcentaje_beneficiario".$i;
                $parentezco = "parentezco_beneficiario".$i;
                $dni_b = "DNI_beneficiario".$i;
            @endphp

            @if($empleado->$nombre)
                <tr>
                    <td>{{ $empleado->$nombre }}</td>
                    <td>{{ $empleado->$parentezco }}</td>
                    <td>{{ $empleado->$porcentaje }}%</td>
                    <td>{{ $empleado->$dni_b }}</td>
                </tr>
            @endif
        @endfor
        </tbody>
    </table>

    <div class="text-end mt-4">
        <a href="{{ route('empleados.index') }}" class="btn btn-secondary">Volver</a>
        <a href="{{ route('empleados.verRegistro.imprimir', $empleado->DNI) }}"
           class="btn btn-primary-custom"
           target="_blank">
            Imprimir
        </a>
    </div>

</div>

@endsection