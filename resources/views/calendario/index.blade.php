@extends('layouts.master')

@section('content')

<div class="container">

    <div class="glass-card p-0 overflow-hidden">

        <div class="row g-0">

            <div class="col-md-3 calendario-lateral d-flex flex-column justify-content-center align-items-center">

                <div class="text-center">

                    <div id="numeroDia" class="dia-grande">
                        --
                    </div>

                    <div id="mesActual" class="mes-texto">
                        ---
                    </div>

                </div>

            </div>


            <div class="col-md-9 p-4">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <h4 class="mb-0">
                        Calendario Institucional
                    </h4>

                    <div>

                        <button class="btn btn-outline-secondary me-2" onclick="importarFeriados()">
                            Importar feriados nacionales
                        </button>

                        <button class="btn btn-dorado" onclick="abrirModalCrear()">
                            Agregar día inhábil
                        </button>

                    </div>

                </div>

                <div id="calendar"></div>

            </div>

        </div>

    </div>

</div>


<!-- MODAL -->

<div class="modal fade" id="modalCalendario">

<div class="modal-dialog">

<div class="modal-content">

<div class="modal-header">

<h5>Día inhábil</h5>

<button class="btn-close" data-bs-dismiss="modal"></button>

</div>


<div class="modal-body">

<input type="text" id="titulo" class="form-control mb-2" placeholder="Título">

<input type="date" id="fecha_inicio" class="form-control mb-2">

<input type="date" id="fecha_fin" class="form-control mb-2">

<select id="origen" class="form-control mb-2">

<option value="nacional">Feriado nacional</option>
<option value="local">Feriado local</option>
<option value="institucional">Institucional</option>

</select>

<textarea id="descripcion" class="form-control" placeholder="Descripción"></textarea>

</div>


<div class="modal-footer">

<button class="btn btn-success" onclick="guardarEvento()">Guardar</button>

</div>

</div>

</div>

</div>

@endsection


<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>



<style>

.calendario-lateral {

    background: #1f3a5f;
    color: white;
    min-height: 420px;

}

.dia-grande {

    font-size: 90px;
    font-weight: 700;
    line-height: 1;

}

.mes-texto {

    font-size: 18px;
    letter-spacing: 2px;
    opacity: .85;

}

.btn-dorado {

    background: #c9a227;
    color: white;
    border: none;

}

.btn-dorado:hover {

    background: #b8951f;
    color: white;

}

.fc .fc-daygrid-day-number {

    color: #1f3a5f;
    font-weight: 500;

}

.fc .fc-day-today {

    background: rgba(201,162,39,0.15);

}

.fc-event {

    border-radius: 6px;
    border: none;

}

.fc-day-sat,
.fc-day-sun {

    background: rgba(0,0,0,0.03);

}
.fc-day-sat,
.fc-day-sun {

    background: #f1f1f1;

}
</style>




<script>

let calendar;

document.addEventListener('DOMContentLoaded', function () {

    const calendarEl = document.getElementById('calendar');

    calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'dayGridMonth',

        locale: 'es',

        height: 'auto',

        headerToolbar: {

            left: 'prev,next today',

            center: 'title',

            right: ''

        },

        buttonText: {

            today: 'Hoy'

        },

        events: '/calendario/eventos',

        // cuando cambias de mes
        datesSet: function(info){

            const fecha = new Date(info.start);

            const dia = fecha.getDate();

            const mes = fecha.toLocaleString('es-ES', { month: 'long', year: 'numeric' });

            document.getElementById('numeroDia').innerText = dia;

            document.getElementById('mesActual').innerText = mes.toUpperCase();

        },

        // click en día
        dateClick: function(info) {

            const fecha = new Date(info.dateStr);

            const dia = fecha.getDate();

            const mes = fecha.toLocaleString('es-ES', { month: 'long', year: 'numeric' });

            document.getElementById('numeroDia').innerText = dia;

            document.getElementById('mesActual').innerText = mes.toUpperCase();

            document.getElementById('fecha_inicio').value = info.dateStr;

            document.getElementById('fecha_fin').value = info.dateStr;

            const modal = new bootstrap.Modal(document.getElementById('modalCalendario'));

            modal.show();

        },

        // click en evento
        eventClick: function(info){

            const evento = info.event;

            document.getElementById('titulo').value = evento.title;

            document.getElementById('fecha_inicio').value = evento.startStr;

            if(evento.end){

                document.getElementById('fecha_fin').value = evento.endStr;

            }else{

                document.getElementById('fecha_fin').value = evento.startStr;

            }

            const modal = new bootstrap.Modal(document.getElementById('modalCalendario'));

            modal.show();

        }

    });

    calendar.render();

});



function abrirModalCrear(){

    document.getElementById('titulo').value = '';

    document.getElementById('descripcion').value = '';

    document.getElementById('fecha_inicio').value = '';

    document.getElementById('fecha_fin').value = '';

    const modal = new bootstrap.Modal(document.getElementById('modalCalendario'));

    modal.show();

}



function guardarEvento(){

    const data = {

        titulo: document.getElementById('titulo').value,
        fecha_inicio: document.getElementById('fecha_inicio').value,
        fecha_fin: document.getElementById('fecha_fin').value,
        origen: document.getElementById('origen').value,
        descripcion: document.getElementById('descripcion').value

    };

    fetch('/calendario/store', {

        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },

        body: JSON.stringify(data)

    })
    .then(res => res.json())
    .then(data => {

        location.reload();

    });

}



function importarFeriados(){

    if(!confirm("Se importarán los feriados nacionales del año actual. ¿Continuar?")){
        return;
    }

    fetch('/calendario/importar-feriados',{

        method:'POST',

        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
        }

    })
    .then(res=>res.json())
    .then(data=>{

        alert("Feriados importados correctamente");

        location.reload();

    });

}

</script>