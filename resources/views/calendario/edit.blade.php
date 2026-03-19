@extends('layouts.master')

@section('content')

<div class="container">

<div class="glass-card p-4">

<h4 class="mb-4">Editar día inhábil</h4>

<form method="POST" action="{{ route('calendario.update',$dia->id) }}">
@csrf
@method('PUT')

{{-- ERRORES --}}
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


{{-- 🔹 DATOS GENERALES --}}
<div class="mb-4">

    <h6 class="text-muted mb-3">Información del día</h6>

    <div class="row">

        <div class="col-md-6 mb-2">
            <label>Título</label>
            <input type="text" name="titulo"
                   value="{{ $dia->titulo }}"
                   class="form-control">
        </div>

        <div class="col-md-3 mb-2">
            <label>Fecha inicio</label>
            <input type="date" name="fecha_inicio"
                   value="{{ $dia->fecha_inicio }}"
                   class="form-control">
        </div>

        <div class="col-md-3 mb-2">
            <label>Fecha fin</label>
            <input type="date" name="fecha_fin"
                   value="{{ $dia->fecha_fin }}"
                   class="form-control">
        </div>

    </div>

    <div class="row mt-2">

        <div class="col-md-6">
            <label>Tipo</label>
            <select name="origen" class="form-control">
                <option value="nacional" @if($dia->origen=='nacional') selected @endif>Nacional</option>
                <option value="local" @if($dia->origen=='local') selected @endif>Local</option>
            </select>
        </div>

        <div class="col-md-6">
            <label>Tipo de afectación</label>
            <select name="tipo_afectacion" class="form-control">
                <option value="no_laborable" @if($dia->tipo_afectacion=='no_laborable') selected @endif>
                    No laborable
                </option>
                <option value="descuento" @if($dia->tipo_afectacion=='descuento') selected @endif>
                    Descuento de días
                </option>
            </select>
        </div>

    </div>

</div>


{{-- 🔹 EXCEPCIONES --}}
<div class="mb-4">

    <h6 class="text-muted mb-2">
        Departamentos que SÍ trabajan (excepción)
    </h6>

    <div class="departamentos-box">

        @php
        $seleccionados = DB::table('calendario_excepciones')
            ->where('calendario_dia_id',$dia->id)
            ->pluck('departamento_id')
            ->toArray();
        @endphp

        @foreach($departamentos as $dep)
            <label class="dep-item">
                <input type="checkbox"
                       name="departamentos[]"
                       value="{{ $dep->id }}"
                       {{ in_array($dep->id,$seleccionados) ? 'checked' : '' }}>
                <span>{{ $dep->nombre }}</span>
            </label>
        @endforeach

    </div>

</div>


{{-- 🔹 DESCRIPCIÓN --}}
<div class="mb-4">

    <label>Descripción</label>

    <textarea name="descripcion"
              class="form-control"
              rows="3">{{ $dia->descripcion }}</textarea>

</div>


{{-- BOTONES --}}

<div class="d-flex justify-content-end">

<a href="{{ route('calendario.index') }}" class="btn btn-outline-secondary mb-3">
    ← Volver al calendario
</a>

    <button class="btn btn-outline-secondary mb-3">
        Actualizar
    </button>
    

</form>

<form action="{{ route('calendario.destroy',$dia->id) }}"
      method="POST"
      onsubmit="return confirm('¿Eliminar este feriado?')">

    @csrf
    @method('DELETE')

    <button class="btn btn-danger">
        Eliminar
    </button>

</form>



</div>

</div>

</div>

@endsection 


<style>

.departamentos-box {
    max-height: 250px;
    overflow-y: auto;
    padding: 10px;
    border: 1px solid #e4e6ea;
    border-radius: 8px;
    background: #f8f9fb;
}

.dep-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 8px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}

.dep-item:hover {
    background: rgba(31,58,95,0.08);
}

.dep-item input {
    accent-color: #1f3a5f;
}

</style>


<script>
document.addEventListener('DOMContentLoaded', function(){

    const inicio = document.querySelector('input[name="fecha_inicio"]');
    const fin = document.querySelector('input[name="fecha_fin"]');

    inicio.addEventListener('change', function(){

        if(inicio.value){
            fin.min = inicio.value; // 🔥 clave
        }

        // si fecha fin es menor, la limpia
        if(fin.value && fin.value < inicio.value){
            fin.value = '';
        }

    });

});
</script>