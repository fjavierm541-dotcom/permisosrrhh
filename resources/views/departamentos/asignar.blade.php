@extends('layouts.master')

@section('title','Asignar empleados')

@section('content')

<div class="glass-card">

    <!-- HEADER -->
    <div class="p-3 text-white"
        style="background:#2f4f6f;border-top-left-radius:18px;border-top-right-radius:18px;">

        <div class="d-flex justify-content-between">

            <h5 class="mb-0">
                Asignar empleados a {{ $departamento->nombre }}
            </h5>

            <a href="{{ route('departamentos.show',$departamento->id) }}"
                class="btn btn-secondary btn-sm">

                Volver

            </a>

        </div>

    </div>


    <div class="p-3">

        <!-- BUSCADOR -->
        <div class="mb-3">

            <input type="text"
                id="buscarEmpleado"
                class="form-control"
                placeholder="Buscar empleado por nombre o DNI">

        </div>


        <!-- BOTONES -->
        <div class="mb-3">

            <button type="button"
                id="seleccionarTodos"
                class="btn btn-sm btn-outline-primary">

                Seleccionar todos

            </button>

            <button type="button"
                id="deseleccionarTodos"
                class="btn btn-sm btn-outline-secondary">

                Quitar selección

            </button>

        </div>


        <form id="formAsignarEmpleados"
            method="POST"
            action="{{ route('departamentos.asignar.guardar',$departamento->id) }}">

            @csrf


            <div class="table-responsive">

                <table class="table align-middle">

                    <thead style="background:#3a5a7c;color:white">

                        <tr>

                            <th width="40"></th>

                            <th>DNI</th>

                            <th>Nombre</th>

                            <th>Puesto</th>

                            <th>Departamento actual</th>

                        </tr>

                    </thead>

                    <tbody id="tablaEmpleados">

                        @foreach($empleados as $emp)

                        <tr>

                            <td>

                                <input type="checkbox"
                                    name="empleados[]"
                                    value="{{ $emp->DNI }}"
                                    data-depto="{{ $emp->departamento->nombre ?? '' }}">

                            </td>

                            <td>
                                {{ $emp->DNI }}
                            </td>

                            <td>

                                {{ $emp->primer_nombre }}  {{ $emp->segundo_nombre }}
                                {{ $emp->primer_apellido }} {{ $emp->segundo_apellido }}

                            </td>

                            <td>
                                {{ $emp->puesto }}
                            </td>

                            <td>

                                @if($emp->departamento)

                                    <span class="badge bg-info">

                                        {{ $emp->departamento->nombre }}

                                    </span>

                                @else

                                    <span class="text-muted">

                                        Sin departamento

                                    </span>

                                @endif

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            <button class="btn btn-primary-custom mt-3">

                Asignar empleados

            </button>

        </form>

    </div>

</div>


<!-- MODAL CONFIRMACIÓN -->
<div class="modal fade" id="confirmarModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header bg-warning">

                <h5 class="modal-title">

                    ⚠ Confirmar movimiento

                </h5>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <p id="mensajeConfirmacion"></p>

            </div>

            <div class="modal-footer">

                <button type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Cancelar

                </button>

                <button type="button"
                    id="confirmarAsignacion"
                    class="btn btn-warning">

                    Sí, mover empleado

                </button>

            </div>

        </div>

    </div>

</div>


<script>

document.addEventListener("DOMContentLoaded", function(){

    const form = document.getElementById("formAsignarEmpleados")
    const modal = new bootstrap.Modal(document.getElementById("confirmarModal"))
    const mensaje = document.getElementById("mensajeConfirmacion")

    let confirmarMovimiento = false

    form.addEventListener("submit", function(e){

        if(confirmarMovimiento) return

        const checks = document.querySelectorAll('input[name="empleados[]"]:checked')

        let conflictos = []

        checks.forEach(function(check){

            const depto = check.dataset.depto

            if(depto !== ""){

                const fila = check.closest("tr")
                const nombre = fila.children[2].innerText

                conflictos.push(nombre + " (" + depto + ")")

            }

        })

        if(conflictos.length > 0){

            e.preventDefault()

            mensaje.innerHTML =
            "Los siguientes empleados ya pertenecen a otro departamento:<br>"
            + conflictos.join("<br>")
            + "<br><br>¿Desea moverlos al nuevo departamento?"

            modal.show()

        }

    })


    document.getElementById("confirmarAsignacion")
    .addEventListener("click", function(){

        confirmarMovimiento = true
        modal.hide()
        form.submit()

    })

})

</script>


<script>

/* BUSCADOR */

document.addEventListener("DOMContentLoaded", function(){

    const buscar = document.getElementById("buscarEmpleado")
    const filas = document.querySelectorAll("#tablaEmpleados tr")

    buscar.addEventListener("keyup", function(){

        const valor = this.value.toLowerCase()

        filas.forEach(function(fila){

            fila.style.display =
                fila.textContent.toLowerCase().includes(valor)
                ? ""
                : "none"

        })

    })


    const seleccionar = document.getElementById("seleccionarTodos")
    const deseleccionar = document.getElementById("deseleccionarTodos")

    seleccionar.addEventListener("click", function(){

        document.querySelectorAll('input[name="empleados[]"]')
        .forEach(c => c.checked = true)

    })

    deseleccionar.addEventListener("click", function(){

        document.querySelectorAll('input[name="empleados[]"]')
        .forEach(c => c.checked = false)

    })

})

</script>

@endsection