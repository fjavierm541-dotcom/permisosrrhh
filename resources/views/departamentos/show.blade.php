@extends('layouts.master')

@section('title','Departamento')

@section('content')

<div class="glass-card p-4">

<h5>

Departamento {{ $departamento->codigo }}

</h5>

<p>

<strong>Nombre:</strong>
{{ $departamento->nombre }}

</p>

<hr>

<h6>

Empleados del departamento

</h6>

@if($departamento->empleados->count())

<table class="table">

<thead>

<tr>
<th>DNI</th>
<th>Nombre</th>
<th>Puesto</th>
</tr>

</thead>

<tbody>

@foreach($departamento->empleados as $emp)

<tr>

<td>{{ $emp->DNI }}</td>

<td>

{{ $emp->primer_nombre }}
{{ $emp->primer_apellido }}

</td>

<td>

{{ $emp->puesto }}

</td>

</tr>

@endforeach

</tbody>

</table>

@else

<div class="alert alert-info">

Este departamento aún no tiene empleados asignados.

</div>

@endif

</div>

@endsection