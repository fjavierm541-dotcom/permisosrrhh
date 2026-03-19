@extends('layouts.master')

@section('content')

<div class="container">

    <div class="glass-card p-4">

        <h4 class="mb-4">Nuevo feriado</h4>

        <form method="POST" action="{{ route('calendario.store') }}">
        @csrf

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
                    <input type="text"
                           name="titulo"
                           class="form-control"
                           placeholder="Ingrese el nombre del feriado"
                           required
                           maxlength="150"
                           pattern="[A-Za-z0-9ÁÉÍÓÚáéíóúñÑ\s\-]+">
                </div>

                <div class="col-md-3 mb-2">
                    <label>Fecha inicio</label>
                    <input type="date"
                           name="fecha_inicio"
                           class="form-control"
                           required>
                </div>

                <div class="col-md-3 mb-2">
                    <label>Fecha fin</label>
                    <input type="date"
                           name="fecha_fin"
                           class="form-control">
                </div>

            </div>

            <div class="row mt-2">

                <div class="col-md-6">
                    <label>Tipo de feriado</label>
                    <select name="origen" class="form-control" required>
                        <option value="nacional">Nacional</option>
                        <option value="local">Local</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Tipo de afectación</label>

                    <select name="tipo_afectacion" class="form-control" required>
                        <option value="no_laborable">No laborable: no labora ningún empleado pero, se descuenta los días.</option>
                        <option value="descuento">Parcialmente laborable: algunos departamentos sí trabajan y no se les decuentan días.</option>
                    </select>
                </div>

            </div>

        </div>


        {{-- 🔹 DESCRIPCIÓN --}}
        <div class="mb-4">

            <label>Descripción</label>

            <textarea name="descripcion"
                      class="form-control"
                      rows="3"
                      placeholder="Describe el motivo del día inhábil..."
                      required></textarea>

        </div>


        {{-- BOTÓN --}}
        
        <div class="d-flex justify-content-end">
            
        <a href="{{ route('calendario.index') }}" class="btn btn-outline-secondary mb-3">
    ← Volver al calendario
</a>

            <button class="btn btn-outline-secondary mb-3">
                Guardar
            </button>

        </div>

        </form>

    </div>

</div>

@endsection


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