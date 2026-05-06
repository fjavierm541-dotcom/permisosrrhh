<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Solicitud de Permiso #{{ $permiso->id }}</title>

<style>

    @page {
        margin: 30px 45px;
    }

    body {
        font-family: Arial, sans-serif;
        color: #111;
        font-size: 12px;
        margin: 0;
    }

    .header {
        text-align: center;
        position: relative;
        margin-bottom: 20px;
    }

    .check-line {
    display: inline-block;
    width: 34px;
    border-bottom: 1px solid #111;
    text-align: center;
    margin-right: 4px;
    font-weight: bold;
}

    .logo-left,
    .logo-right {
        position: absolute;
        top: 0;
        width: 60px;
        height: 60px;
        border: 1px solid #bbb;
        text-align: center;
        line-height: 60px;
        font-size: 10px;
        color: #666;
    }

    .logo-left {
        left: 0;
    }

    .logo-right {
        right: 0;
    }

    .municipio {
        font-size: 16px;
        font-weight: bold;
    }

    .sub {
        font-size: 10px;
        line-height: 1.3;
    }

    .titulo {
        margin-top: 10px;
        font-size: 24px;
        font-weight: bold;
    }

    .subtitulo {
        font-size: 11px;
        font-weight: bold;
    }

    .section {
        margin-top: 15px;
    }

    .strong {
        font-weight: bold;
    }

    .form-row {
        display: flex;
        align-items: end;
        gap: 10px;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }

    .form-group {
        display: flex;
        align-items: end;
        gap: 5px;
    }

    .form-label-inline {
        font-weight: bold;
        white-space: nowrap;
    }

    .line {
        display: inline-block;
        border-bottom: 1px solid #111;
        min-height: 16px;
        padding: 0 4px;
        font-size: 12px;
        line-height: 16px;
    }

    .line-name {
        width: 300px;
    }

    .line-code {
        width: 70px;
        text-align: center;
    }

    .line-depto {
        width: 360px;
    }

    .line-date {
        width: 140px;
        text-align: center;
    }

    .permisos {
        margin-top: 12px;
        margin-bottom: 12px;
        line-height: 2;
    }

    .permiso-item {
        margin-right: 14px;
    }

    .observaciones {
        margin-top: 16px;
    }

    .obs-title {
        margin-bottom: 8px;
    }

    .obs-line {
        border: 1px solid #999;
        border-radius: 8px;
        padding: 10px 12px;
        min-height: 65px;
        margin-bottom: 10px;

        font-size: 12px;
        line-height: 1.4;

        white-space: pre-wrap;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .signatures {
        margin-top: 70px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        column-gap: 90px;
        row-gap: 52px;
    }

    .firma-box {
        text-align: center;
    }

    .firma-line {
        border-top: 1px solid #111;
        margin-bottom: 6px;
        height: 1px;
    }

    .footer {
        margin-top: 58px;
        display: flex;
        justify-content: space-between;
        align-items: end;
        font-size: 10px;
    }

</style>
</head>

<body>

@php

    $empleado = $permiso->empleado;

    $nombreCompleto =
        ($empleado->primer_nombre ?? '') . ' ' .
        ($empleado->segundo_nombre ?? '') . ' ' .
        ($empleado->primer_apellido ?? '') . ' ' .
        ($empleado->segundo_apellido ?? '');

    $deptoFuncional =
        $empleado->departamentoFuncional->nombre ?? '—';

    $jefeRRHH =
        \App\Models\DepartamentoMuni::with('jefe')
            ->where('nombre', 'LIKE', '%RECURSOS HUMANOS%')
            ->first();

    $nombreJefeRRHH =
        optional($jefeRRHH?->jefe)->primer_nombre . ' ' .
        optional($jefeRRHH?->jefe)->primer_apellido;

    $tipo = strtolower($permiso->tipo->nombre ?? '');

    $presentaJustificacion = $permiso->documento ? 'SI' : 'NO';

    $observaciones = $permiso->motivo;

    $fechaInicio = \Carbon\Carbon::parse($permiso->fecha_inicio);

    $fechaFin = $permiso->fecha_fin
        ? \Carbon\Carbon::parse($permiso->fecha_fin)
        : null;

    $fechaTramite = \Carbon\Carbon::parse($permiso->created_at);

    $diasOtorgados = match($permiso->modalidad) {
        'horas' => $permiso->horas . ' hora(s)',
        'medio_dia' => 'Medio día',
        'un_dia' => '1 día',
        'varios_dias' =>
            $fechaInicio->diffInDays($fechaFin) + 1 . ' días',
        default => '—'
    };

@endphp

<div class="header">

    <div class="logo-left">
        LOGO
    </div>

    <div class="logo-right">
        LOGO
    </div>

    <div class="municipio">
        MUNICIPALIDAD DE DANLÍ
    </div>

    <div class="sub">
        Departamento de El Paraíso<br>
        HONDURAS C.A.<br>
        Tel. 2763-2290, 2763-2080 Fax (504) 2763-2638<br>
        E-Mail: munidanli@hondutel.hn
    </div>

    <div class="titulo">
        SOLICITUD
    </div>

    <div class="subtitulo">
        USAR ESTE FORMATO PARA PERMISOS DE UN DÍA O MÁS
    </div>

</div>

<div class="section">

    <div class="strong">
        PARA:
        {{ strtoupper(trim($nombreJefeRRHH)) }}
    </div>

    <div class="strong" style="margin-top:4px;">
        DEPARTAMENTO DE RECURSOS HUMANOS
    </div>

</div>

<div class="section">

    <div class="form-row">

        <div class="form-group">
            <span class="form-label-inline">DE:</span>

            <span class="line line-name">
                {{ trim($nombreCompleto) }}
            </span>
        </div>

        <div class="form-group">
            <span class="form-label-inline">
                No. DE EMPLEADO:
            </span>

            <span class="line line-code">
                {{ $empleado->codigo ?? '—' }}
            </span>
        </div>

    </div>

    <div class="form-row">

        <div class="form-group">
            <span class="form-label-inline">
                DEPARTAMENTO ASIGNADO:
            </span>

            <span class="line line-depto">
                {{ $deptoFuncional }}
            </span>
        </div>

    </div>

</div>

<div class="section">

    <div class="strong">
        ASUNTO: SOLICITUD DE PERMISO
        <span style="font-size:10px; font-weight:normal;">
            (anotar con una X la razón de su ausencia)
        </span>
    </div>

    <div class="permisos">

    <span class="permiso-item">
        <span class="check-line">
            {{ $tipo == 'compensatorio' ? 'X' : '' }}
        </span>
        COMPENSATORIO
    </span>

    <span class="permiso-item">
        <span class="check-line">
            {{ $tipo == 'personal' ? 'X' : '' }}
        </span>
        PERSONAL
    </span>

    <span class="permiso-item">
        <span class="check-line">
            {{ str_contains($tipo, 'médica') || str_contains($tipo, 'medica') ? 'X' : '' }}
        </span>
        CITA MÉDICA
    </span>

    <span class="permiso-item">
        <span class="check-line">
            {{ $tipo == 'vacaciones' ? 'X' : '' }}
        </span>
        VACACIONES
    </span>

    <span class="permiso-item">
        <span class="check-line">
            {{ $tipo == 'fúnebre' || $tipo == 'funebre' ? 'X' : '' }}
        </span>
        FÚNEBRE
    </span>

    <span class="permiso-item">
        <span class="check-line">
            {{ $tipo == 'sindical' ? 'X' : '' }}
        </span>
        SINDICAL
    </span>

</div>

</div>

<div class="section">

    <div class="strong">
        ¿PRESENTA JUSTIFICACIÓN DE LA AUSENCIA?
        <span style="font-size:10px; font-weight:normal;">
            (en caso de ser necesaria):
        </span>

        SI ___ {{ $presentaJustificacion == 'SI' ? 'X' : '' }}

        &nbsp;&nbsp;&nbsp;

        NO ___ {{ $presentaJustificacion == 'NO' ? 'X' : '' }}
    </div>

</div>

<div class="section">

    <div class="form-row">

        <div class="form-group">
            <span class="form-label-inline">
                DIA(S) OTORGADOS:
            </span>

            <span class="line line-date">
                {{ $diasOtorgados }}
            </span>
        </div>

        <div class="form-group">
            <span class="form-label-inline">
                DESDE:
            </span>

            <span class="line line-date">
                {{ $fechaInicio->format('d / m / Y') }}
            </span>
        </div>

        <div class="form-group">
            <span class="form-label-inline">
                HASTA:
            </span>

            <span class="line line-date">
                {{ $fechaFin
                    ? $fechaFin->format('d / m / Y')
                    : $fechaInicio->format('d / m / Y') }}
            </span>
        </div>

    </div>

</div>

<div class="observaciones">

    <div class="obs-title">
        <strong>OBSERVACIONES:</strong>
    </div>

    <div class="obs-line">
        {{ $observaciones ?: 'Sin observaciones registradas.' }}
    </div>

</div>

<div class="signatures">

    <div class="firma-box">
        <div class="firma-line"></div>
        <strong>FIRMA EMPLEADO</strong>
    </div>

    <div class="firma-box">
        <div class="firma-line"></div>
        <strong>V.B. JEFE DE DEPARTAMENTO</strong>
    </div>

    <div class="firma-box">
        <div class="firma-line"></div>
        <strong>JEFE DE RECURSOS HUMANOS</strong>
    </div>

    <div class="firma-box">
        <div class="firma-line"></div>
        <strong>V.B. GERENCIA ADMINISTRATIVA FINANCIERA</strong>
    </div>

</div>

<div class="footer">

    <div>
        <strong>FECHA DE TRÁMITE:</strong>

        <span class="line line-date">
            {{ $fechaTramite->format('d / m / Y') }}
        </span>
    </div>

    <div>
        FORMATO MODIFICADO 15-06-2022
    </div>

</div>

<script>
    window.print();
</script>

</body>
</html>