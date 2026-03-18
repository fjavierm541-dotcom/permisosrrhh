@extends('layouts.master')

@section('content')

<div class="container">

<div class="glass-card p-4">

<h4>Nuevo día inhábil</h4>

<form method="POST" action="{{ route('calendario.store') }}">
@csrf

<input type="text" name="titulo" class="form-control mb-2" placeholder="Título">

<input type="date" name="fecha_inicio" class="form-control mb-2">

<input type="date" name="fecha_fin" class="form-control mb-2">

<select name="origen" class="form-control mb-2">
<option value="nacional">Nacional</option>
<option value="local">Local</option>
</select>

<textarea name="descripcion" class="form-control mb-2"></textarea>

<button class="btn btn-success">Guardar</button>

</form>

</div>

</div>

@endsection