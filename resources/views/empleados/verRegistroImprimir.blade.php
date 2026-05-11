<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro Oficial de Personal</title>

<style>
    @page {
        size: letter;
        margin: 10mm;
    }

    body {
        font-family: Arial, sans-serif;
        font-size: 9.5px;
        color: #000;
        margin: 0;
        padding: 0;
    }

    .hoja {
        width: 100%;
        margin: 0 auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    td, th {
        border: 1px solid #000;
        padding: 4px 5px;
        vertical-align: middle;
    }

    .center { text-align: center; }
    .right { text-align: right; }
    .bold { font-weight: bold; }
    .small { font-size: 8px; }

    .titulo {
        font-size: 12px;
        font-weight: bold;
    }

    .seccion {
        background: #e9e9e9;
        font-weight: bold;
        text-align: center;
        text-transform: uppercase;
    }

    .label {
        font-weight: bold;
        text-align: center;
        background: #f7f7f7;
    }

    .logo {
        width: 52px;
        height: 52px;
        object-fit: contain;
    }

    .foto-box {
        height: 72px;
        text-align: center;
        font-weight: bold;
    }

    .firma-box {
        height: 65px;
        text-align: center;
        vertical-align: bottom;
        font-weight: bold;
    }

    .huella-box {
        width: 120px;
        height: 65px;
        text-align: center;
        vertical-align: bottom;
        font-weight: bold;
    }

    .beneficiarios td {
        height: 17px;
    }

    .sin-borde {
        border: none;
    }
</style>
</head>

<body>

@php
    $fechaNacimiento = '';

    if ($empleado->dia_nacimiento && $empleado->mes_nacimiento && $empleado->anio_nacimiento) {
        $fechaNacimiento =
            sprintf('%02d', $empleado->dia_nacimiento) . '/' .
            sprintf('%02d', $empleado->mes_nacimiento) . '/' .
            $empleado->anio_nacimiento;
    }
@endphp

<div class="hoja">

    {{-- ENCABEZADO --}}
    <table>
        <tr>
            <td style="width: 16%;" class="center">
                <img src="{{ public_path('img/logoescudo.png') }}" class="logo">
            </td>

            <td style="width: 68%;" class="center">
                <div class="small bold">MUNICIPALIDAD DE DANLÍ</div>
                <div class="small bold">Departamento de Personal</div>
                <div class="small">Tel. 2763-2280, 2763-2080 Fax 2763-2638</div>
                <br>
                <div class="titulo">Datos del Personal</div>
            </td>

            <td style="width: 16%;" class="center">
                <img src="{{ public_path('img/logomuni.png') }}" class="logo">
            </td>
        </tr>
    </table>

    {{-- DATOS GENERALES --}}
    <table>
        <tr>
            <td colspan="4" class="seccion">Datos Generales</td>
            <td rowspan="5" class="foto-box" style="width: 18%;">Foto</td>
        </tr>

        <tr>
            <td class="label" style="width: 18%;">Código de Empleado</td>
            <td style="width: 27%;">{{ $empleado->codigo }}</td>
            <td class="label" style="width: 18%;">No. DNI</td>
            <td style="width: 19%;">{{ $empleado->DNI }}</td>
        </tr>

        <tr>
            <td class="label">Primer Nombre</td>
            <td>{{ $empleado->primer_nombre }}</td>
            <td class="label">Segundo Nombre</td>
            <td>{{ $empleado->segundo_nombre }}</td>
        </tr>

        <tr>
            <td class="label">Primer Apellido</td>
            <td>{{ $empleado->primer_apellido }}</td>
            <td class="label">Segundo Apellido</td>
            <td>{{ $empleado->segundo_apellido }}</td>
        </tr>

        <tr>
            <td class="label">Fecha de Nacimiento</td>
            <td>{{ $fechaNacimiento }}</td>
            <td class="label">Sexo</td>
            <td>{{ $empleado->sexo }}</td>
        </tr>

        <tr>
            <td class="label">RTN</td>
            <td>{{ $empleado->RTN }}</td>
            <td class="label">Estado Civil</td>
            <td colspan="2">{{ $empleado->estado_civil }}</td>
        </tr>

        <tr>
            <td class="label">Nacionalidad</td>
            <td>{{ $empleado->nacionalidad }}</td>
            <td class="label">Tipo de Sangre</td>
            <td colspan="2">{{ $empleado->tipo_sangre }}</td>
        </tr>
    </table>

    {{-- DOMICILIO --}}
    <table>
        <tr>
            <td colspan="6" class="seccion">Dirección de Domicilio</td>
        </tr>

        <tr>
            <td colspan="6">{{ $empleado->direccion_domicilio }}</td>
        </tr>

        <tr>
            <td class="label" style="width: 22%;">Otra Referencia de Domicilio</td>
            <td style="width: 28%;">{{ $empleado->referencia_domicilio }}</td>
            <td class="label" style="width: 14%;">No. Celular</td>
            <td style="width: 14%;">{{ $empleado->telefono_celular }}</td>
            <td class="label" style="width: 12%;">No. Fijo</td>
            <td style="width: 10%;">{{ $empleado->telefono_fijo }}</td>
        </tr>
    </table>

    {{-- NIVEL EDUCATIVO --}}
    <table>
        <tr>
            <td colspan="4" class="seccion">Nivel Educativo</td>
        </tr>

        <tr>
            <td class="label" style="width: 25%;">Nivel educativo</td>
            <td colspan="3">{{ $empleado->nivel_educativo }}</td>
        </tr>
    </table>

    {{-- CONTACTOS DE EMERGENCIA --}}
    <table>
        <tr>
            <td colspan="4" class="seccion">
                En caso de emergencia autorizo llamar a las siguientes personas en este orden
            </td>
        </tr>

        <tr>
            <td colspan="4" class="label">Contacto de Emergencia 1</td>
        </tr>

        <tr>
            <td class="label" style="width: 22%;">Nombre Completo</td>
            <td colspan="3">{{ $empleado->nombre_contacto1 }}</td>
        </tr>

        <tr>
            <td class="label">Número de Teléfono</td>
            <td>{{ $empleado->telefono_contacto1 }}</td>
            <td class="label">Parentesco</td>
            <td>{{ $empleado->parentezco_contacto1 }}</td>
        </tr>

        <tr>
            <td colspan="4" class="label">Contacto de Emergencia 2</td>
        </tr>

        <tr>
            <td class="label">Nombre Completo</td>
            <td colspan="3">{{ $empleado->nombre_contacto2 }}</td>
        </tr>

        <tr>
            <td class="label">Número de Teléfono</td>
            <td>{{ $empleado->telefono_contacto2 }}</td>
            <td class="label">Parentesco</td>
            <td>{{ $empleado->parentezco_contacto2 }}</td>
        </tr>
    </table>

    {{-- BENEFICIARIOS --}}
    <table class="beneficiarios">
        <tr>
            <td colspan="5" class="seccion">Beneficiarios en caso de muerte</td>
        </tr>

        <tr class="label">
            <td style="width: 8%;">No.</td>
            <td>Nombre</td>
            <td style="width: 14%;">Porcentaje</td>
            <td style="width: 20%;">Parentesco</td>
            <td style="width: 22%;">No. DNI</td>
        </tr>

        @for ($i = 1; $i <= 7; $i++)
            @php
                $nombre = "nombre_beneficiario".$i;
                $porcentaje = "porcentaje_beneficiario".$i;
                $parentezco = "parentezco_beneficiario".$i;
                $dni_b = "DNI_beneficiario".$i;

                $hay = $empleado->$nombre && $empleado->$nombre !== 'Vacío';
            @endphp

            <tr>
                <td class="center">{{ $i }}</td>
                <td>{{ $hay ? $empleado->$nombre : '' }}</td>
                <td class="center">{{ $hay ? $empleado->$porcentaje.'%' : '' }}</td>
                <td>{{ $hay ? $empleado->$parentezco : '' }}</td>
                <td>{{ $hay ? $empleado->$dni_b : '' }}</td>
            </tr>
        @endfor
    </table>

    {{-- FIRMA / HUELLA --}}
    <table>
        <tr>
            <td class="firma-box">Firma de Empleado</td>
            <td class="huella-box">Huella</td>
        </tr>
    </table>

    {{-- ESPACIO DEL PATRONO --}}
    <table>
        <tr>
            <td colspan="4" class="seccion">Espacio del Patrono</td>
        </tr>

        <tr>
            <td class="label" style="width: 22%;">Puesto de Nombramiento</td>
            <td style="width: 28%;">{{ $empleado->puesto }}</td>
            <td class="label" style="width: 22%;">Fecha de Nombramiento</td>
            <td style="width: 28%;">{{ $empleado->fecha_nombramiento }}</td>
        </tr>

        <tr>
            <td class="label">Tipo de nombramiento</td>
            <td>{{ $empleado->tipo }}</td>
            <td class="label">Salario Inicial L.</td>
            <td>{{ number_format($empleado->salario_inicial ?? 0, 2) }}</td>
        </tr>

        @if($empleado->tipo === 'Contrato')
            <tr>
                <td class="label">Fecha fin de contrato</td>
                <td colspan="3">{{ $empleado->fecha_fin_contrato ?? '' }}</td>
            </tr>
        @endif
    </table>

</div>

</body>
</html>