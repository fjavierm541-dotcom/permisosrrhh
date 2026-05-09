<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Solicitud de Permiso #{{ $permiso->id }}</title>

<style>
    @page {
        size: letter;
        margin: 20px 34px;
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        color: #111;
        font-size: 11.5px;
        margin: 0;
    }

    .documento {
        border: 1px solid #cfcfcf;
        border-radius: 10px;
        padding: 16px 20px;
    }

    /* =========================
       ENCABEZADO
    ========================= */
    .header {
        position: relative;
        border-bottom: 2px solid #1f3a56;
        padding: 8px 78px 12px 78px;
        margin-bottom: 14px;
        min-height: 92px;
        text-align: center;
    }

    .logo-left,
    .logo-right {
        position: absolute;
        top: 12px;
        width: 58px;
        height: 58px;
    }

    .logo-left {
        left: 10px;
    }

    .logo-right {
        right: 10px;
    }

    .logo-left img,
    .logo-right img {
        width: 58px;
        height: 58px;
        object-fit: contain;
        display: block;
    }

    .header-content {
        width: 100%;
        max-width: 430px;
        margin: 0 auto;
        text-align: center;
    }

    .municipio {
        font-size: 14px;
        font-weight: bold;
        letter-spacing: .3px;
        line-height: 1.1;
    }

    .sub {
        font-size: 8.3px;
        line-height: 1.15;
        margin-top: 2px;
    }

    .titulo {
        margin-top: 6px;
        font-size: 22px;
        font-weight: bold;
        letter-spacing: 1px;
        line-height: 1;
    }

    .subtitulo {
        font-size: 9.8px;
        font-weight: bold;
        margin-top: 3px;
    }

    /* =========================
       CLASIFICACIÓN
    ========================= */
    .clasificacion {
        background: #eef3f8;
        border-left: 6px solid #1f3a56;
        border-radius: 8px;
        padding: 8px 12px;
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    .clasificacion strong {
        font-size: 11.5px;
    }

    .tipo-clasificacion {
        font-size: 13.5px;
        font-weight: bold;
        color: #1f3a56;
        text-transform: uppercase;
        text-align: right;
    }

    /* =========================
       FORMULARIO
    ========================= */
    .section {
        margin-top: 10px;
    }

    .strong {
        font-weight: bold;
    }

    .form-row {
        display: flex;
        align-items: flex-end;
        gap: 10px;
        margin-bottom: 8px;
        flex-wrap: wrap;
    }

    .form-group {
        display: flex;
        align-items: flex-end;
        gap: 5px;
        min-width: 0;
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
        font-size: 11.5px;
        line-height: 16px;
        overflow: hidden;
        white-space: nowrap;
    }

    .line-name {
        width: 285px;
    }

    .line-code {
        width: 55px;
        text-align: center;
    }

    .line-depto {
        width: 365px;
    }

    .line-date {
        width: 118px;
        text-align: center;
    }

    .line-small {
        width: 95px;
        text-align: center;
    }

    /* =========================
       BLOQUES
    ========================= */
    .bloque-asunto {
        background: #fafafa;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 8px 10px;
        margin-top: 9px;
    }

    .permisos {
        margin-top: 8px;
        line-height: 1.9;
    }

    .permiso-item {
        margin-right: 11px;
        white-space: nowrap;
        display: inline-block;
    }

    .check-line {
        display: inline-block;
        width: 30px;
        border-bottom: 1px solid #111;
        text-align: center;
        margin-right: 4px;
        font-weight: bold;
        line-height: 14px;
    }

    .datos-tiempo {
        background: #f7f9fb;
        border: 1px solid #d8dde3;
        border-radius: 8px;
        padding: 8px 10px;
        margin-top: 10px;
    }

    .observaciones {
        margin-top: 11px;
    }

    .obs-title {
        margin-bottom: 5px;
    }

    .obs-line {
        border: 1px solid #999;
        border-radius: 8px;
        padding: 10px 12px;
        min-height: 58px;
        font-size: 11.5px;
        line-height: 1.4;
        white-space: pre-wrap;
        word-wrap: break-word;
        overflow-wrap: break-word;
        background: #fff;
    }

    /* =========================
       FIRMAS Y PIE
    ========================= */
    .signatures {
        margin-top: 52px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        column-gap: 82px;
        row-gap: 42px;
    }

    .firma-box {
        text-align: center;
        font-size: 10.3px;
    }

    .firma-line {
        border-top: 1px solid #111;
        margin-bottom: 6px;
        height: 1px;
    }

    .footer {
        margin-top: 42px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        font-size: 9.5px;
    }

    @media print {
        body {
            margin: 0;
        }
    }
</style>
</head>

<body>

@php
    $empleado = $permiso->empleado;

    $nombreCompleto = trim(
        ($empleado->primer_nombre ?? '') . ' ' .
        ($empleado->segundo_nombre ?? '') . ' ' .
        ($empleado->primer_apellido ?? '') . ' ' .
        ($empleado->segundo_apellido ?? '')
    );

    $deptoFuncional = $empleado->departamentoFuncional->nombre ?? '—';

    $jefeRRHH =
        \App\Models\DepartamentoMuni::with('jefe')
            ->where('nombre', 'LIKE', '%RECURSOS HUMANOS%')
            ->first();

    $nombreJefeRRHH = trim(
        (optional($jefeRRHH?->jefe)->primer_nombre ?? '') . ' ' .
        (optional($jefeRRHH?->jefe)->segundo_nombre ?? '') . ' ' .
        (optional($jefeRRHH?->jefe)->primer_apellido ?? '') . ' ' .
        (optional($jefeRRHH?->jefe)->segundo_apellido ?? '')
    );

    $tipo = strtolower($permiso->tipo->nombre ?? '');
    $presentaJustificacion = $permiso->documento ? 'SI' : 'NO';
    $observaciones = $permiso->motivo;

    $fechaInicio = \Carbon\Carbon::parse($permiso->fecha_inicio);
    $fechaFin = $permiso->fecha_fin ? \Carbon\Carbon::parse($permiso->fecha_fin) : null;
    $fechaTramite = \Carbon\Carbon::parse($permiso->created_at);

    $esPermisoTiempo = in_array($permiso->modalidad, ['horas', 'medio_dia']);

    $clasificacionTexto = $esPermisoTiempo
        ? 'PERMISO POR HORA(S)'
        : 'PERMISO POR DÍA(S)';

    $formatearHoras = function ($horas) {

        $horas = (float) $horas;

        $horasEnteras = floor($horas);

        $minutos = round(($horas - $horasEnteras) * 60);

        if ($minutos == 60) {
            $horasEnteras++;
            $minutos = 0;
        }

        $texto = '';

        if ($horasEnteras > 0) {
            $texto .= $horasEnteras . 'h ';
        }

        if ($minutos > 0) {
            $texto .= $minutos . 'min';
        }

        return trim($texto) ?: '0min';
    };

    if ($permiso->modalidad === 'horas') {

        $tiempoSolicitado = $formatearHoras($permiso->horas);

        $diasOtorgados = '—';

    } elseif ($permiso->modalidad === 'medio_dia') {

        $tiempoSolicitado = '4h';

        $diasOtorgados = 'Medio día';

    } elseif ($permiso->modalidad === 'un_dia') {

        $tiempoSolicitado = '—';

        $diasOtorgados = '1 día';

    } else {

        $tiempoSolicitado = '—';

        $diasOtorgados = $fechaFin
            ? ($fechaInicio->diffInDays($fechaFin) + 1) . ' días'
            : '—';
    }
@endphp

<div class="documento">

    <div class="header">

        <div class="logo-left">
            <img src="{{ asset('img/logomuni.png') }}" alt="Logo Municipalidad">
        </div>

        <div class="logo-right">
            <img src="{{ asset('img/logoescudo.png') }}" alt="Escudo Municipalidad">
        </div>

        <div class="header-content">

            <div class="municipio">
                MUNICIPALIDAD DE DANLÍ
            </div>

            <div class="sub">
                Departamento de El Paraíso<br>
                HONDURAS C.A.<br>
                Tel. 2763-2290, 2763-2080 Fax (504) 2763-2638<br>
                E-Mail: munidanli@hondutel.hn
            </div>

            <div class="titulo">SOLICITUD</div>

            <div class="subtitulo">
                FORMATO GENERAL PARA PERMISOS LABORALES
            </div>

        </div>

    </div>

    <div class="clasificacion">

        <strong>CLASIFICACIÓN DEL PERMISO:</strong>

        <div class="tipo-clasificacion">
            {{ $clasificacionTexto }}
        </div>

    </div>

    <div class="section">

        <div class="strong">
            PARA: {{ strtoupper($nombreJefeRRHH ?: 'JEFE DE RECURSOS HUMANOS') }}
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
                    {{ $nombreCompleto }}
                </span>
            </div>

            <div class="form-group">
                <span class="form-label-inline">No. DE EMPLEADO:</span>

                <span class="line line-code">
                    {{ $empleado->codigo ?? '—' }}
                </span>
            </div>

        </div>

        <div class="form-row">

            <div class="form-group">
                <span class="form-label-inline">DEPARTAMENTO ASIGNADO:</span>

                <span class="line line-depto">
                    {{ $deptoFuncional }}
                </span>
            </div>

        </div>

    </div>

    <div class="bloque-asunto">

        <div class="strong">
            ASUNTO: SOLICITUD DE PERMISO
            <span style="font-size:10px; font-weight:normal;">
                (anotar con una X la razón de su ausencia)
            </span>
        </div>

        <div class="permisos">

            <span class="permiso-item">
                <span class="check-line">{{ $tipo == 'compensatorio' ? 'X' : '' }}</span>
                COMPENSATORIO
            </span>

            <span class="permiso-item">
                <span class="check-line">{{ $tipo == 'personal' ? 'X' : '' }}</span>
                PERSONAL
            </span>

            <span class="permiso-item">
                <span class="check-line">{{ str_contains($tipo, 'médica') || str_contains($tipo, 'medica') ? 'X' : '' }}</span>
                CITA MÉDICA
            </span>

            <span class="permiso-item">
                <span class="check-line">{{ $tipo == 'vacaciones' ? 'X' : '' }}</span>
                VACACIONES
            </span>

            <span class="permiso-item">
                <span class="check-line">{{ $tipo == 'fúnebre' || $tipo == 'funebre' ? 'X' : '' }}</span>
                FÚNEBRE
            </span>

            <span class="permiso-item">
                <span class="check-line">{{ $tipo == 'sindical' ? 'X' : '' }}</span>
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

            SI
            <span class="check-line">
                {{ $presentaJustificacion == 'SI' ? 'X' : '' }}
            </span>

            &nbsp;&nbsp;&nbsp;

            NO
            <span class="check-line">
                {{ $presentaJustificacion == 'NO' ? 'X' : '' }}
            </span>

        </div>

    </div>

    <div class="datos-tiempo">

        {{-- PERMISOS POR DÍA --}}
        <div class="form-row">

            <div class="form-group">
                <span class="form-label-inline">DIA(S) OTORGADOS:</span>

                <span class="line line-small">
                    {{ !$esPermisoTiempo ? $diasOtorgados : '—' }}
                </span>
            </div>

            <div class="form-group">
                <span class="form-label-inline">DESDE:</span>

                <span class="line line-date">
                    {{ $fechaInicio->format('d / m / Y') }}
                </span>
            </div>

            <div class="form-group">
                <span class="form-label-inline">HASTA:</span>

                <span class="line line-date">
                    {{ $fechaFin ? $fechaFin->format('d / m / Y') : $fechaInicio->format('d / m / Y') }}
                </span>
            </div>

        </div>

        {{-- PERMISOS POR HORAS --}}
        <div class="form-row" style="margin-bottom:0;">

            <div class="form-group">
                <span class="form-label-inline">HORA DE SALIDA:</span>

                <span class="line line-small">
                    {{ data_get($permiso, 'hora_salida')
                        ? \Carbon\Carbon::parse(data_get($permiso, 'hora_salida'))->format('h:i A')
                        : '—' }}
                </span>
            </div>

            <div class="form-group">
                <span class="form-label-inline">HORA DE ENTRADA:</span>

                <span class="line line-small">
                    {{ data_get($permiso, 'hora_entrada')
                        ? \Carbon\Carbon::parse(data_get($permiso, 'hora_entrada'))->format('h:i A')
                        : '—' }}
                </span>
            </div>

            <div class="form-group">
                <span class="form-label-inline">TIEMPO TOMADO:</span>

                <span class="line line-small">
                    {{ $esPermisoTiempo ? $tiempoSolicitado : '—' }}
                </span>
            </div>

        </div>

    </div>

    <div class="observaciones">

        <div class="obs-title">
            <strong>
                OBSERVACIONES / RAZÓN EN CASO DE SER LABORAL, PERSONAL O COMPENSATORIO:
            </strong>
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
            FORMATO MODIFICADO MAYO 2026
        </div>

    </div>

</div>

<script>
    window.print();
</script>

</body>
</html>