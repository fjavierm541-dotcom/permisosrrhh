@extends('layouts.master')

@section('title','Jefe de departamento')

@section('content')

<div class="glass-card">

    <!-- HEADER -->
    <div class="p-3 text-white"
        style="background:#2f4f6f;border-top-left-radius:18px;border-top-right-radius:18px;">

        <div class="d-flex justify-content-between">

            <h5 class="mb-0">
                Asignar jefe – {{ $departamento->nombre }}
            </h5>

            <a href="{{ route('departamentos.show',$departamento->id) }}"
                class="btn btn-secondary btn-sm">

                Volver

            </a>

        </div>

    </div>


    <div class="p-4">

        <form method="POST"
            action="{{ route('departamentos.jefe.guardar',$departamento->id) }}">

            @csrf


            <div class="mb-3">

                <label class="form-label">

                    Jefe de departamento

                </label>

                <select name="jefe_dni"
                        class="form-control">

                    <option value="">
                        Sin jefe
                    </option>

                    @foreach($empleados as $emp)

                        <option value="{{ $emp->DNI }}"
                            @if($departamento->jefe_dni == $emp->DNI) selected @endif>

                            {{ $emp->primer_nombre }}
                            {{ $emp->primer_apellido }}

                        </option>

                    @endforeach

                </select>

                @error('jefe_dni')

                    <div class="text-danger mt-1">

                        {{ $message }}

                    </div>

                @enderror

            </div>


            <button class="btn btn-primary-custom">

                Guardar jefe

            </button>


            <a href="{{ route('departamentos.show',$departamento->id) }}"
                class="btn btn-secondary">

                Cancelar

            </a>

        </form>

    </div>

</div>

@endsection