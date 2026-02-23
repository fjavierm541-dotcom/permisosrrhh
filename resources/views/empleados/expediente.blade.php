@extends('layouts.master')

@section('title', 'Expediente')

@section('content')

<div class="glass-card p-4">

    <h4 class="fw-bold mb-3">
        Expediente de documentos de {{ $empleado->primer_nombre }} {{ $empleado->primer_apellido }}
    </h4>

    @php
        $tipos = [
            'Copia DNI',
            'Acuerdo',
            'Nota Traslado'
        ];
    @endphp

    <table class="table table-bordered text-center">

        <thead>
            <tr>
                <th>Documento</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>

        <tbody>

        @foreach($tipos as $tipo)

            @php
                $doc = $empleado->documentos
                    ->where('tipo_documento', $tipo)
                    ->first();
            @endphp

            <tr>
                <td>{{ $tipo }}</td>

                <td>
                    @if($doc)
                        <span class="badge bg-success">Disponible</span>
                    @else
                        <span class="badge bg-danger">No cargado</span>
                    @endif
                </td>

                <td>
                    @if($doc)
                        <a href="{{ asset('storage/'.$doc->ruta_archivo) }}"
                           target="_blank"
                           class="btn btn-sm btn-dark">
                            Ver
                        </a>
                    @else
                        —
                    @endif
                </td>
            </tr>

        @endforeach

        </tbody>

    </table>

    <div class="mt-3">
        <a href="{{ route('empleados.index') }}"
           class="btn btn-secondary">
           Volver
        </a>
    </div>

</div>

@endsection