@extends('layouts.master')

@section('title', 'Solicitud de Compensatorio')

@section('content')

<div class="glass-card p-4">

    <!-- HEADER -->
    <div class="mb-4">
        <h4 class="fw-bold text-dark">
            Solicitud de Día No Laboral
        </h4>
    </div>

    <!-- MENSAJES -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FORM -->
    <form method="POST" action="{{ route('compensatorios.solicitudes.store') }}">
        @csrf

        <!-- DEPARTAMENTO -->
        <div class="mb-3">
            <label class="form-label">Departamento</label>
            <select name="departamento_id" class="form-control" required>
                <option value="">Seleccione</option>
                @foreach($departamentos as $dep)
                    <option value="{{ $dep->id }}">
                        {{ $dep->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- FECHA -->
        <div class="mb-3">
            <label class="form-label">Fecha trabajada</label>
            <input type="date" name="fecha_trabajada" class="form-control" required>
        </div>

        <!-- EMPLEADOS -->
        <div class="mb-3">
    <label class="form-label">Empleados</label>

    <select name="empleados[]"
        id="empleados-select"
        class="form-control"
        multiple
        required>
    </select>

    <small class="text-muted">
        Selecciona uno o varios empleados
    </small>

    @error('empleados')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

        <!-- DESCRIPCIÓN -->
        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control"></textarea>
        </div>

        <!-- JUSTIFICACIÓN -->
        <div class="mb-3">
            <label class="form-label">Justificación (si es tardío)</label>
            <textarea name="justificacion" class="form-control"></textarea>
        </div>

        <!-- BOTÓN -->
        <div class="text-end">
            <button type="submit" class="btn btn-primary-custom">
                Guardar solicitud
            </button>
        </div>

    </form>
</div>

<script>
document.querySelector('[name="departamento_id"]').addEventListener('change', function(){

    let deptoId = this.value;
    let select = document.getElementById('empleados-select');

    select.innerHTML = '<option>Cargando...</option>';

    if(deptoId){

        fetch(`/empleados/por-departamento/${deptoId}`)
        .then(res => res.json())
        .then(data => {

            select.innerHTML = '';

            data.forEach(emp => {

                let option = document.createElement('option');

                option.value = emp.DNI; // ✔ correcto
                option.text = emp.nombre; // ✔ ya concatenado

                select.appendChild(option);

            });

        });

    }else{
        select.innerHTML = '';
    }

});
</script>




@endsection