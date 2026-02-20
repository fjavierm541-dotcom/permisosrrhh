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
    left: 0px;
    right: 0px;
    height: 60px;
    text-align: center;
    line-height: 18px;
}

footer {
    position: fixed;
    bottom: -60px;
    left: 0px;
    right: 0px;
    height: 40px;
    text-align: center;
    font-size: 10px;
}

h2 {
    margin-bottom: 5px;
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
<strong>Fecha de nombramiento:</strong>
{{ \Carbon\Carbon::parse($empleado->fecha_nombramiento)
    ->locale('es')
    ->translatedFormat('d \d\e F \d\e\l Y') }}<br>
<strong>Días disponibles actuales:</strong> {{ $totalDiasDisponibles }} días
</p>

<div class="section-title">Períodos Activos</div>

<table>
<thead>
<tr>
<th>Año</th>
<th>Días otorgados</th>
<th>Días usados</th>
<th>Días restantes</th>
<th>Vencimiento</th>
</tr>
</thead>
<tbody>
@forelse($periodosActivos as $periodo)
<tr>
<td>{{ $periodo->anio_laboral }}</td>
<td>{{ $periodo->dias_otorgados }}</td>
<td>{{ $periodo->dias_usados }}</td>
<td>{{ $periodo->dias_otorgados - $periodo->dias_usados }}</td>
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

<div class="section-title">Períodos Vencidos</div>

<table>
<thead>
<tr>
<th>Año</th>
<th>Días otorgados</th>
<th>Días usados</th>
<th>Vencimiento</th>
</tr>
</thead>
<tbody>
@forelse($periodosVencidos as $periodo)
<tr>
<td>{{ $periodo->anio_laboral }}</td>
<td>{{ $periodo->dias_otorgados }}</td>
<td>{{ $periodo->dias_usados }}</td>
<td>{{ \Carbon\Carbon::parse($periodo->fecha_vencimiento)->format('d-m-Y') }}</td>
</tr>
@empty
<tr>
<td colspan="4">No hay períodos vencidos.</td>
</tr>
@endforelse
</tbody>
</table>

<div class="section-title">Historial de Movimientos</div>

<table>
<thead>
<tr>
<th>Fecha</th>
<th>Tipo</th>
<th>Descripción</th>
<th>Días</th>
<th>Horas</th>
</tr>
</thead>
<tbody>
@forelse($movimientos as $movimiento)
<tr>
<td>{{ $movimiento->created_at->format('d-m-Y H:i') }}</td>
<td>{{ $movimiento->tipo_movimiento }}</td>
<td>{{ $movimiento->descripcion }}</td>
<td>{{ $movimiento->dias_afectados ?? '-' }}</td>
<td>{{ $movimiento->horas_afectadas ?? '-' }}</td>
</tr>
@empty
<tr>
<td colspan="5">No hay movimientos registrados.</td>
</tr>
@endforelse
</tbody>
</table>


<!-- 🔥 PAGINACIÓN  -->
<script type="text/php">
if (isset($pdf)) {

    $font = $fontMetrics->get_font("DejaVu Sans", "normal");
    $size = 9;

    $pageText = "Página {PAGE_NUM} de {PAGE_COUNT}";

    $x = ($pdf->get_width() - 100);
    $y = ($pdf->get_height() - 30);

    $pdf->page_text($x, $y, $pageText, $font, $size);
}
</script>



</body>
</html>
