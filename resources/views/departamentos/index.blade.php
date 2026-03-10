@extends('layouts.master')

@section('title','Departamentos')

@section('content')

<div class="glass-card">

    <!-- HEADER -->
    <div class="p-3 text-white"
    style="background:#2f4f6f;border-top-left-radius:18px;border-top-right-radius:18px;">

        <div class="d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                Listado de Departamentos
            </h5>

            <div>

                <a href="{{ route('departamentos.create') }}"
                class="btn btn-primary-custom btn-sm">
                    Registrar Departamento
                </a>

                <a href="{{ route('paginas.inicio') }}"
                class="btn btn-secondary btn-sm">
                    Atrás
                </a>

            </div>

        </div>

    </div>


    <!-- BUSCADOR -->

   <!-- BUSCADOR -->
<div class="p-3">

    <form id="formBusqueda" method="GET" action="{{ route('departamentos.index') }}" class="mb-3">

        <div class="row g-2">

            <div class="col-md-10">

                <input
                type="text"
                id="buscarInput"
                name="buscar"
                class="form-control"
                placeholder="Buscar por nombre de depto. o código"
                value="{{ $buscar ?? '' }}">

            </div>

            <div class="col-md-2">

                <a href="{{ route('departamentos.index') }}"
                class="btn btn-secondary w-100">

                Limpiar

                </a>

            </div>

        </div>

    </form>

</div>


    <!-- TABLA -->

    <div class="table-responsive">

        <table class="table align-middle mb-0">

            <thead style="background:#3a5a7c;color:white">

                <tr>

                    <th width="60">#</th>

                    <th width="120">Código</th>

                    <th>Departamento</th>

                    <th width="120">Estado</th>

                    <th width="260">Acciones</th>

                </tr>

            </thead>

            <tbody id="tablaDepartamentos">

                @forelse($departamentos as $i => $dep)

                <tr>

                    <td>
                        {{ $departamentos->firstItem() + $i }}
                    </td>

                    <td>
                        <strong>{{ $dep->codigo }}</strong>
                    </td>

                    <td>
                        {{ $dep->nombre }}
                    </td>

                    <td>

                        @if($dep->activo)

                        <span class="badge bg-success">
                            Activo
                        </span>

                        @else

                        <span class="badge bg-secondary">
                            Inactivo
                        </span>

                        @endif

                    </td>

                    <td>

                        <a href="{{ route('departamentos.show',$dep->id) }}"
                        class="btn btn-dark btn-sm">
                            Ver
                        </a>

                        <a href="{{ route('departamentos.edit',$dep->id) }}"
                        class="btn btn-warning btn-sm">
                            Editar
                        </a>

                        <form method="POST"
                        action="{{ route('departamentos.toggle',$dep->id) }}"
                        class="d-inline">

                            @csrf
                            @method('PATCH')

                            <button class="btn btn-outline-secondary btn-sm">

                                {{ $dep->activo ? 'Desactivar' : 'Activar' }}

                            </button>

                        </form>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="5"
                    class="text-center text-muted">

                        No hay departamentos registrados

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    <!-- PAGINACIÓN -->

    <div class="mt-3 d-flex justify-content-center">

    {{ $departamentos->links() }}

</div>

</div>





<!-- TABLA -->

<script>

document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('formBusqueda')
    const input = document.getElementById('buscarInput')

    let timer = null

    input.addEventListener('keyup', function(){

        clearTimeout(timer)

        timer = setTimeout(() => {

            form.submit()

        }, 600)

    })

})


</script>

@endsection