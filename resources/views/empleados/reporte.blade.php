<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Reporte Empleado</title>

	<style>
		@page {
			margin: 100px 40px 80px 40px;
		}

		body {
			font-family: DejaVu Sans, sans-serif;
			font-size: 11px;
		}

		header {
			position: fixed;
			top: -80px;
			left: 0;
			right: 0;
			text-align: center;
			line-height: 18px;
		}

		footer {
			position: fixed;
			bottom: -60px;
			left: 0;
			right: 0;
			text-align: center;
			font-size: 10px;
		}

		table {
			width: 100%;
			border-collapse: collapse;
			margin-bottom: 15px;
		}

		table, th, td {
			border: 1px solid #444;
		}

		th {
			background-color: #f0f0f0;
		}

		th, td {
			padding: 5px;
			text-align: center;
		}

		.section-title {
			margin-top: 20px;
			font-weight: bold;
		}

		.resumen {
			margin-bottom: 10px;
		}
	</style>
</head>

<body>

<header>
	<strong>SISTEMA DE PERMISOS RRHH</strong><br>
	Reporte individual de vacaciones y permisos
</header>

<footer>
	Generado el {{ $fechaGeneracion }}
</footer>

<p>
	<strong>Nombre:</strong> {{ $empleado->primer_nombre }} {{ $empleado->primer_apellido }}<br>
	<strong>DNI:</strong> {{ $empleado->DNI }}<br>
	<strong>Departamento:</strong> {{ $empleado->departamentoFuncional->nombre ?? 'Sin asignar' }}<br>
</p>

<div class="resumen">
	<strong>Total de días disponibles: {{ $totalGeneral }}</strong><br><br>

	Compensatorios: <strong>{{ $diasCompensatorios }}</strong> días<br>
	Vacaciones: <strong>{{ $totalDiasDisponibles }}</strong> días
</div>

<!-- ========================== -->
<!-- 🔵 PERÍODOS ACTIVOS -->
<!-- ========================== -->

<div class="section-title">Períodos Activos (Histórico)</div>

<table>
	<thead>
		<tr>
			<th>Año</th>
			<th>Vacaciones</th>
			<th>Compensatorios</th>
			<th>Total</th>
			<th>Vencimiento</th>
		</tr>
	</thead>

	<tbody>
		@forelse($periodosActivos as $periodo)

			@php
                $anio = $periodo->anio_laboral;
                $vacaciones = $periodo->dias_otorgados;
                $compensatorios = $diasCompensatoriosPorAnio[$anio] ?? 0;
                $total = $vacaciones + $compensatorios;
            @endphp

			<tr>
				<td>{{ $anio }}</td>
				<td>{{ $vacaciones }}</td>
				<td>{{ $compensatorios }}</td>
				<td>{{ $total }}</td>
				<td>
					{{ \Carbon\Carbon::parse($periodo->extension_hasta ?? $periodo->fecha_vencimiento)->format('d-m-Y') }}
				</td>
			</tr>

		@empty
			<tr>
				<td colspan="5">No hay períodos activos.</td>
			</tr>
		@endforelse
	</tbody>
</table>

<!-- ========================== -->
<!-- 🔴 PERÍODOS VENCIDOS -->
<!-- ========================== -->

<div class="section-title">Períodos Vencidos</div>

<table>
	<thead>
		<tr>
			<th>Año</th>
			<th>Vacaciones</th>
			<th>Compensatorios</th>
			<th>Total</th>
			<th>Usados</th>
			<th>Vencimiento</th>
		</tr>
	</thead>

	<tbody>
		@forelse($periodosVencidos as $periodo)

			@php
				$anio = $periodo->anio_laboral;
				$vacaciones = $periodo->dias_otorgados;
				$compensatorios = $diasCompensatoriosPorAnio[$anio] ?? 0;
				$total = $vacaciones + $compensatorios;
			@endphp

			<tr>
				<td>{{ $anio }}</td>
				<td>{{ $vacaciones }}</td>
				<td>{{ $compensatorios }}</td>
				<td>{{ $total }}</td>
				<td>{{ $periodo->dias_usados }}</td>
				<td>
					{{ \Carbon\Carbon::parse($periodo->fecha_vencimiento)->format('d-m-Y') }}
				</td>
			</tr>

		@empty
			<tr>
				<td colspan="6">No hay períodos vencidos.</td>
			</tr>
		@endforelse
	</tbody>
</table>

<!-- ========================== -->
<!-- 🟡 MOVIMIENTOS -->
<!-- ========================== -->

<div class="section-title">Historial de Movimientos</div>

<table>
	<thead>
		<tr>
			<th>Fecha</th>
			<th>Tipo</th>
			<th>Descripción</th>
			<th>Días</th>
		</tr>
	</thead>

	<tbody>
		@forelse($movimientos as $movimiento)
			<tr>
				<td>{{ $movimiento->created_at->format('d-m-Y H:i') }}</td>
				<td>{{ ucfirst(str_replace('_',' ', $movimiento->tipo_movimiento)) }}</td>
				<td>{{ $movimiento->descripcion }}</td>
				<td>{{ $movimiento->dias_afectados ?? '-' }}</td>
			</tr>
		@empty
			<tr>
				<td colspan="4">No hay movimientos registrados.</td>
			</tr>
		@endforelse
	</tbody>
</table>

<!-- ========================== -->
<!-- 📄 PAGINACIÓN -->
<!-- ========================== -->

<script type="text/php">
if (isset($pdf)) {

	$font = $fontMetrics->get_font("DejaVu Sans", "normal");

	$pdf->page_text(
		($pdf->get_width() / 2) - 50,
		$pdf->get_height() - 30,
		"Página {PAGE_NUM} de {PAGE_COUNT}",
		$font,
		9
	);
}
</script>

</body>
</html>