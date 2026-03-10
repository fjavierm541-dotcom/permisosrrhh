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

    <div class="p-3">

        <div class="row g-2">

            <div class="col-md-10">

                <input
                type="text"
                id="buscarDepto"
                class="form-control"
                placeholder="Buscar por nombre o código"
                value="{{ $buscar ?? '' }}">

            </div>

            <div class="col-md-2">

                <button
                id="limpiarBusqueda"
                class="btn btn-secondary w-100">

                Limpiar

                </button>

            </div>

        </div>

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

    <div class="p-3">

    {{ $departamentos->links() }}

</div>

</div>


<!-- BUSCADOR EN TIEMPO REAL -->
<script>

function normalizarTexto(texto){

    return texto
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g,"")

}

document.getElementById("buscarDepto")
.addEventListener("keyup", function(){

    const valor = normalizarTexto(this.value)

    const filas = document.querySelectorAll("#tablaDepartamentos tr")

    filas.forEach(function(fila){

        const textoFila = normalizarTexto(fila.textContent)

        fila.style.display = textoFila.includes(valor) ? "" : "none"

    })

})


// LIMPIAR

document.getElementById("limpiarBusqueda")
.addEventListener("click", function(){

    const input = document.getElementById("buscarDepto")

    input.value = ""

    const filas = document.querySelectorAll("#tablaDepartamentos tr")

    filas.forEach(f => f.style.display = "")

})

</script>



@endsection