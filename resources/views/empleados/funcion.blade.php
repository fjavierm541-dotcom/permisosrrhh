@extends('layouts.master')

@section('title','Asignación funcional')

@section('content')

<div class="glass-card p-4">

<h5 class="fw-bold mb-4">

Asignación funcional de empleado

</h5>

<p>

<strong>Empleado:</strong>

{{ $empleado->primer_nombre }}
{{ $empleado->primer_apellido }}

</p>

<form method="POST"
      action="{{ route('empleados.funcion.guardar',$empleado->DNI) }}">

@csrf

<div class="mb-3">

<label class="form-label">

Departamento funcional

</label>

<select name="departamento_funcional_id"
        class="form-control">

<option value="">Sin asignación</option>

@foreach($departamentos as $d)

<option value="{{ $d->id }}"
@if($empleado->departamento_funcional_id == $d->id) selected @endif>

{{ $d->codigo }} - {{ $d->nombre }}

</option>

@endforeach

</select>

</div>

<button class="btn btn-primary-custom">

Guardar asignación

</button>

<a href="{{ route('empleados.verRegistro',$empleado->DNI) }}"
class="btn btn-secondary">

Volver

</a>

</form>

</div>

@endsection