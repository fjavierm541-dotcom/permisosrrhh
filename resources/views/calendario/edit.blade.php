@extends('layouts.master')

@section('content')

<div class="container">

<div class="glass-card p-4">

<h4>Editar día inhábil</h4>

<form method="POST" action="{{ route('calendario.update',$dia->id) }}">
@csrf
@method('PUT')

<input type="text" name="titulo" value="{{ $dia->titulo }}" class="form-control mb-2">

<input type="date" name="fecha_inicio" value="{{ $dia->fecha_inicio }}" class="form-control mb-2">

<input type="date" name="fecha_fin" value="{{ $dia->fecha_fin }}" class="form-control mb-2">

<select name="origen" class="form-control mb-2">
<option value="nacional" @if($dia->origen=='nacional') selected @endif>Nacional</option>
<option value="local" @if($dia->origen=='local') selected @endif>Local</option>
</select>

<textarea name="descripcion" class="form-control mb-2">{{ $dia->descripcion }}</textarea>





<button class="btn btn-primary">Actualizar</button>

</form>

<form action="{{ route('calendario.destroy',$dia->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este feriado?')">
    @csrf
    @method('DELETE')

    <button class="btn btn-danger mt-2">
        Eliminar
    </button>
</form>



</div>

</div>

@endsection