<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registro Personal</title>

    <style>
        @page {
            size: letter;
            margin: 20px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .titulo {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        td, th {
            border: 1px solid black;
            padding: 4px;
        }

        .sin-borde td {
            border: none;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .espacio {
            height: 25px;
        }
    </style>
</head>

<body>

{{-- ENCABEZADO --}}
<table class="sin-borde">
    <tr>
        <td class="center bold">
            MUNICIPALIDAD DE DANLÍ<br>
            Departamento de El Paraíso
        </td>
    </tr>
</table>

<p class="titulo">Datos del Personal</p>

{{-- DATOS PRINCIPALES --}}
<table>
    <tr>
        <td class="bold">Código de Empleado</td>
        <td>{{ $empleado->codigo }}</td>
        <td class="bold center">Foto</td>
    </tr>
</table>

<table>
    <tr>
        <td class="bold">Primer Nombre</td>
        <td>{{ $empleado->primer_nombre }}</td>
        <td class="bold">Segundo Nombre</td>
        <td>{{ $empleado->segundo_nombre }}</td>
    </tr>
    <tr>
        <td class="bold">Primer Apellido</td>
        <td>{{ $empleado->primer_apellido }}</td>
        <td class="bold">Segundo Apellido</td>
        <td>{{ $empleado->segundo_apellido }}</td>
    </tr>
</table>

<table>
    <tr>
        <td class="bold">No. DNI</td>
        <td>{{ $empleado->DNI }}</td>
        <td class="bold">RTN</td>
        <td>{{ $empleado->RTN }}</td>
    </tr>
</table>

<table>
    <tr>
        <td class="bold">Estado Civil</td>
        <td>{{ $empleado->estado_civil }}</td>
        <td class="bold">Nacionalidad</td>
        <td>{{ $empleado->nacionalidad }}</td>
        <td class="bold">Tipo de Sangre</td>
        <td>{{ $empleado->tipo_sangre }}</td>
    </tr>
</table>

{{-- DIRECCIÓN --}}
<table>
    <tr>
        <td class="bold">Dirección de Domicilio</td>
        <td colspan="5">{{ $empleado->direccion_domicilio }}</td>
    </tr>
</table>

<table>
    <tr>
        <td class="bold">Otra Referencia</td>
        <td>{{ $empleado->referencia_domicilio }}</td>
        <td class="bold">No. Celular</td>
        <td>{{ $empleado->telefono_celular }}</td>
        <td class="bold">No. Fijo</td>
        <td>{{ $empleado->telefono_fijo }}</td>
    </tr>
</table>

{{-- CONTACTOS --}}
<table>
    <tr>
        <td colspan="3" class="bold center">
            En caso de emergencia autoriza llamar a la siguiente persona en ese orden
        </td>
    </tr>
    <tr>
        <td class="bold">Contacto (1)</td>
        <td>{{ $empleado->nombre_contacto1 }}</td>
        <td>{{ $empleado->telefono_contacto1 }}</td>
    </tr>
    <tr>
        <td class="bold">Contacto (2)</td>
        <td>{{ $empleado->nombre_contacto2 }}</td>
        <td>{{ $empleado->telefono_contacto2 }}</td>
    </tr>
</table>

{{-- BENEFICIARIOS --}}
<table>
    <tr>
        <td colspan="4" class="bold center">
            BENEFICIARIOS EN CASO DE MUERTE
        </td>
    </tr>
    <tr>
        <th>Nombre</th>
        <th>Parentezco</th>
        <th>Porcentaje</th>
        <th>DNI</th>
    </tr>

    @for ($i = 1; $i <= 7; $i++)
        @php
            $nombre = "nombre_beneficiario".$i;
            $parentezco = "parentezco_beneficiario".$i;
            $porcentaje = "porcentaje_beneficiario".$i;
            $dni_b = "DNI_beneficiario".$i;
        @endphp

        <tr>
            <td>{{ $empleado->$nombre }}</td>
            <td>{{ $empleado->$parentezco }}</td>
            <td>{{ $empleado->$porcentaje }}</td>
            <td>{{ $empleado->$dni_b }}</td>
        </tr>
    @endfor
</table>

{{-- ESPACIO FIRMA --}}
<br><br>

<table>
    <tr>
        <td class="center">Firma de Empleado</td>
        <td class="center">Huella</td>
    </tr>
    <tr>
        <td class="espacio"></td>
        <td></td>
    </tr>
</table>

{{-- ESPACIO DEL PATRONO --}}
<table>
    <tr>
        <td colspan="3" class="bold center">ESPACIO DEL PATRONO</td>
    </tr>
    <tr>
        <td class="bold">Puesto de Nombramiento</td>
        <td>{{ $empleado->puesto }}</td>
        <td class="bold">Fecha Nombramiento</td>
        <td>{{ $empleado->fecha_nombramiento }}</td>
    </tr>
    <tr>
        <td class="bold">Tipo</td>
        <td>{{ $empleado->tipo }}</td>
        <td class="bold">Salario Inicial</td>
        <td>L. {{ number_format($empleado->salario_inicial, 2) }}</td>
    </tr>
</table>

<script>
    window.onload = function() {
        window.print();
    }
</script>

</body>
</html>