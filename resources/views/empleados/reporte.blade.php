<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Reporte Empleado</title>

<style>
	@page {
		margin: 95px 32px 70px 32px;
	}

	body {
		font-family: DejaVu Sans, sans-serif;
		font-size: 11px;
		color: #111;
	}

	header {
		position: fixed;
		top: -78px;
		left: 0;
		right: 0;
		text-align: center;
		line-height: 1.35;
	}

	footer {
		position: fixed;
		bottom: -48px;
		left: 0;
		right: 0;
		text-align: center;
		font-size: 10px;
		color: #444;
	}

	.titulo-sistema {
		font-size: 12px;
		font-weight: bold;
	}

	.subtitulo-sistema {
		font-size: 10px;
	}

	p {
		margin: 0 0 8px 0;
	}

	.resumen {
		margin: 10px 0 12px 0;
		line-height: 1.6;
	}

	.section-title {
		margin-top: 18px;
		margin-bottom: 6px;
		font-weight: bold;
		font-size: 12px;
	}

	table {
		width: 100%;
		border-collapse: collapse;
		margin-bottom: 12px;
		table-layout: fixed;
	}

	table, th, td {
		border: 1px solid #444;
	}

	th {
		background-color: #f0f0f0;
		font-weight: bold;
	}

	th, td {
		padding: 6px 5px;
		text-align: center;
		vertical-align: middle;
		word-wrap: break-word;
	}

	/* Tablas de períodos */
	.tabla-periodos {
		font-size: 10.5px;
	}

	/* Historial un poco más compacto, pero con aire */
	.tabla-movimientos {
		font-size: 9px;
	}

	.text-left {
		text-align: left;
	}

	.text-small {
		font-size: 9px;
	}

	.nowrap {
		white-space: nowrap;
	}
</style>
</head>

<body>

<header>
	<div class="titulo-sistema">SISTEMA DE PERMISOS RRHH</div>
	<div class="subtitulo-sistema">Reporte individual de vacaciones y permisos</div>
</header>

<footer>
	Generado el {{ $fechaGeneracion }}
</footer>

<p>
	<strong>Nombre:</strong> {{ $empleado->primer_nombre }} {{ $empleado->primer_apellido }}<br>
	<strong>DNI:</strong> {{ $empleado->DNI }}<br>
	<strong>Departamento:</strong> {{ $empleado->departamentoFuncional->nombre ?? 'Sin asignar' }}
</p>

<div class="resumen">
	<strong>Total disponible: {{ $totalGeneral }} días</strong><br>
	Compensatorios: {{ $diasCompensatorios }} | Vacaciones: {{ $totalDiasDisponibles }}
</div>

<div class="section-title">Períodos Activos</div>

<table class="tabla-periodos">
	<thead>
		<tr>
			<th style="width: 12%;">Año</th>
			<th style="width: 19%;">Vacaciones</th>
			<th style="width: 22%;">Compensatorios</th>
			<th style="width: 14%;">Total</th>
			<th style="width: 33%;">Vencimiento</th>
		</tr>
	</thead>

	<tbody>
		@forelse($periodosActivos as $periodo)
			@php
				$anio = $periodo->anio_laboral;
				$vac = $periodo->dias_otorgados;
				$comp = $diasCompensatoriosPorAnio[$anio] ?? 0;
			@endphp

			<tr>
				<td>{{ $anio }}</td>
				<td>{{ $vac }}</td>
				<td>{{ $comp }}</td>
				<td>{{ $vac + $comp }}</td>
				<td>{{ \Carbon\Carbon::parse($periodo->extension_hasta ?? $periodo->fecha_vencimiento)->format('d-m-Y') }}</td>
			</tr>
		@empty
			<tr>
				<td colspan="5">No hay períodos activos.</td>
			</tr>
		@endforelse
	</tbody>
</table>

<div class="section-title">Períodos Vencidos</div>

<table class="tabla-periodos">
	<thead>
		<tr>
			<th style="width: 10%;">Año</th>
			<th style="width: 17%;">Vacaciones</th>
			<th style="width: 20%;">Compensatorios</th>
			<th style="width: 12%;">Total</th>
			<th style="width: 12%;">Usados</th>
			<th style="width: 29%;">Vencimiento</th>
		</tr>
	</thead>

	<tbody>
		@forelse($periodosVencidos as $periodo)
			@php
				$anio = $periodo->anio_laboral;
				$vac = $periodo->dias_otorgados;
				$comp = $diasCompensatoriosPorAnio[$anio] ?? 0;
			@endphp

			<tr>
				<td>{{ $anio }}</td>
				<td>{{ $vac }}</td>
				<td>{{ $comp }}</td>
				<td>{{ $vac + $comp }}</td>
				<td>{{ $periodo->dias_usados }}</td>
				<td>{{ \Carbon\Carbon::parse($periodo->fecha_vencimiento)->format('d-m-Y') }}</td>
			</tr>
		@empty
			<tr>
				<td colspan="6">No hay períodos vencidos.</td>
			</tr>
		@endforelse
	</tbody>
</table>

<div class="section-title">Historial de Movimientos</div>

<table class="tabla-movimientos">
	<thead>
		<tr>
			<th style="width: 13%;">Fecha</th>
			<th style="width: 12%;">Tipo</th>
			<th style="width: 11%;">Estado</th>
			<th style="width: 14%;">Rango</th>
			<th style="width: 6%;">Días</th>
			<th style="width: 34%;">Descripción</th>
			<th style="width: 10%;">Aprobado por</th>
		</tr>
	</thead>

	<tbody>
		@forelse($movimientos as $movimiento)
			@php
				$permiso = $permisos[$movimiento->permiso_id] ?? null;

				$tipo = ucfirst($movimiento->categoria ?? 'Movimiento');
				$estado = $permiso->estado->nombre ?? '—';

				$inicio = $permiso->fecha_inicio ?? null;
				$fin = $permiso->fecha_fin ?? null;
			@endphp

			<tr>
				<td class="nowrap">{{ $movimiento->created_at->format('d-m-Y H:i') }}</td>
				<td>{{ $tipo }}</td>
				<td>{{ $estado }}</td>

				<td>
					@if($inicio)
						{{ \Carbon\Carbon::parse($inicio)->format('d-m-Y') }}
						@if($fin && $fin != $inicio)
							<br>
							{{ \Carbon\Carbon::parse($fin)->format('d-m-Y') }}
						@endif
					@else
						—
					@endif
				</td>

				<td>{{ $movimiento->dias_afectados ?? 0 }}</td>
				<td class="text-left">{{ $movimiento->descripcion }}</td>
				<td>—</td>
			</tr>
		@empty
			<tr>
				<td colspan="7">No hay movimientos.</td>
			</tr>
		@endforelse
	</tbody>
</table>

<script type="text/php">
if (isset($pdf)) {
	$font = $fontMetrics->get_font("DejaVu Sans", "normal");
	$size = 9;

	$footerText = "Página {PAGE_NUM}/{PAGE_COUNT}";
	$textWidth = $fontMetrics->get_text_width($footerText, $font, $size);

	$x = ($pdf->get_width() - $textWidth) / 2;
	$y = $pdf->get_height() - 18;

	$pdf->page_text($x, $y, $footerText, $font, $size);
}
</script>

</body>
</html>