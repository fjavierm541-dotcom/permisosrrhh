@extends('layouts.master')

@section('title','Registrar departamento')

@section('content')

<div class="glass-card">

    <!-- HEADER -->
    <div class="p-3 text-white"
        style="background:#2f4f6f;border-top-left-radius:18px;border-top-right-radius:18px;">

        <div class="d-flex justify-content-between">

            <h5 class="mb-0">
                Registrar departamento
            </h5>

            <a href="{{ route('departamentos.index') }}"
                class="btn btn-secondary btn-sm">

                Volver

            </a>

        </div>

    </div>


    <div class="p-4">

        <!-- ERRORES -->
        @if($errors->any())

            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form method="POST"
            action="{{ route('departamentos.store') }}">

            @csrf


            <div class="row">

                <div class="col-md-4">

                    <label class="form-label">
                        Código
                    </label>

                    <input type="text"
                        name="codigo"
                        id="codigoDepto"
                        maxlength="3"
                        class="form-control"
                        required>

                </div>


                <div class="col-md-8">

                    <label class="form-label">
                        Nombre del departamento
                    </label>

                    <input type="text"
                        name="nombre"
                        id="nombreDepto"
                        maxlength="150"
                        class="form-control"
                        required>

                </div>

            </div>


            <div class="mt-3">

                <label class="form-label">
                    Descripción
                </label>

                <textarea
                    name="descripcion"
                    id="descripcionDepto"
                    class="form-control"
                    maxlength="255"
                    rows="3"></textarea>

            </div>


            <div class="mt-3">

                <label class="form-label">
                    Depende de
                </label>

                <select name="departamento_padre_id"
                    class="form-control">

                    <option value="">
                        Ninguno
                    </option>

                    @foreach($padres as $dep)

                        <option value="{{ $dep->id }}">

                            {{ $dep->codigo }} - {{ $dep->nombre }}

                        </option>

                    @endforeach

                </select>

            </div>


            <div class="mt-4">

                <button class="btn btn-primary-custom">

                    Guardar departamento

                </button>

                <a href="{{ route('departamentos.index') }}"
                    class="btn btn-secondary">

                    Cancelar

                </a>

            </div>

        </form>

    </div>

</div>


<script>

document.addEventListener("DOMContentLoaded", function(){

    const codigo = document.getElementById("codigoDepto")
    const nombre = document.getElementById("nombreDepto")
    const descripcion = document.getElementById("descripcionDepto")

    /* SOLO NÚMEROS EN CÓDIGO */

    codigo.addEventListener("keypress", function(e){

        if(!/[0-9]/.test(e.key)){
            e.preventDefault()
        }

    })


    /* BLOQUEAR PEGADO NO NUMÉRICO */

    codigo.addEventListener("paste", function(e){

        const texto = (e.clipboardData || window.clipboardData).getData('text')

        if(!/^\d+$/.test(texto)){
            e.preventDefault()
        }

    })


    /* TEXTO SOLO LETRAS Y TILDES */

    function validarTexto(input){

        input.addEventListener("keypress", function(e){

            const regex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]$/

            if(!regex.test(e.key)){
                e.preventDefault()
            }

        })

    }

    validarTexto(nombre)
    validarTexto(descripcion)

})

</script>

@endsection