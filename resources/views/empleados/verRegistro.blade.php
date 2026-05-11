@extends('layouts.master')

@section('title', 'Ver Registro')

@section('content')

<style>
    .registro-card {
        background: rgba(255,255,255,.96);
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,.35);
    }

    .registro-header {
        background: linear-gradient(135deg, #1f3a56, #2d4f73);
        color: white;
        padding: 24px;
        border-radius: 16px;
        margin-bottom: 24px;
    }

    .registro-header h4 {
        margin: 0;
        letter-spacing: .5px;
    }

    .registro-subtitle {
        opacity: .85;
        font-size: 13px;
        margin-top: 4px;
    }

    .section-title {
        font-weight: 700;
        color: #1f3a56;
        margin: 24px 0 12px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e5edf5;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        border: 1px solid #dce4ec;
        border-radius: 14px;
        overflow: hidden;
        background: white;
    }

    .info-item {
        padding: 13px 15px;
        border-right: 1px solid #e5edf5;
        border-bottom: 1px solid #e5edf5;
    }

    .info-item:nth-child(4n) {
        border-right: none;
    }

    .info-label {
        font-size: 12px;
        font-weight: 700;
        color: #52677a;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 14px;
        color: #111827;
        font-weight: 500;
        word-break: break-word;
    }

    .info-wide {
        grid-column: span 4;
    }

    .info-half {
        grid-column: span 2;
    }

    .badge-status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        background: #e8f1fb;
        color: #1f3a56;
    }

    .btn-funcional {
        background: #eef6ff;
        color: #1f3a56;
        border: 1px solid #bcd7f0;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        padding: 8px 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all .2s ease;
    }

    .btn-funcional:hover {
        background: #1f3a56;
        color: white;
        border-color: #1f3a56;
        transform: translateY(-1px);
    }

    .beneficiarios-table {
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #dce4ec;
    }

    .beneficiarios-table thead th {
        background: #1f3a56;
        color: white;
        font-size: 13px;
        padding: 12px;
    }

    .beneficiarios-table tbody td {
        padding: 12px;
        vertical-align: middle;
    }

    .empty-box {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 14px;
        padding: 18px;
        color: #64748b;
        text-align: center;
    }

    .action-bar {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        margin-top: 24px;
    }

    @media (max-width: 992px) {
        .info-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .info-item:nth-child(4n) {
            border-right: 1px solid #e5edf5;
        }

        .info-item:nth-child(2n) {
            border-right: none;
        }

        .info-wide,
        .info-half {
            grid-column: span 2;
        }
    }

    @media (max-width: 576px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .info-item,
        .info-item:nth-child(2n),
        .info-item:nth-child(4n) {
            border-right: none;
        }

        .info-wide,
        .info-half {
            grid-column: span 1;
        }

        .action-bar {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>

@php
    $fechaNacimiento = '-';

    if ($empleado->dia_nacimiento && $empleado->mes_nacimiento && $empleado->anio_nacimiento) {
        $fechaNacimiento = sprintf('%02d', $empleado->dia_nacimiento)
            . '/'
            . sprintf('%02d', $empleado->mes_nacimiento)
            . '/'
            . $empleado->anio_nacimiento;
    }

    $beneficiarios = [];

    for ($i = 1; $i <= 7; $i++) {
        $nombre = "nombre_beneficiario".$i;
        $porcentaje = "porcentaje_beneficiario".$i;
        $parentezco = "parentezco_beneficiario".$i;
        $dni_b = "DNI_beneficiario".$i;

        if ($empleado->$nombre && $empleado->$nombre !== 'Vacío') {
            $beneficiarios[] = [
                'nombre' => $empleado->$nombre,
                'porcentaje' => $empleado->$porcentaje,
                'parentezco' => $empleado->$parentezco,
                'dni' => $empleado->$dni_b,
            ];
        }
    }

    $totalBeneficiarios = collect($beneficiarios)->sum(function ($beneficiario) {
    return (int) ($beneficiario['porcentaje'] ?? 0);
});
@endphp

<div class="glass-card p-4 registro-card">

    <div class="registro-header">
        <h4 class="fw-bold">Registro Oficial de Personal</h4>
        <div class="registro-subtitle">
            Información general, laboral, contactos, beneficiarios y auditoría del empleado.
        </div>
    </div>

    {{-- DATOS GENERALES --}}
    <h5 class="section-title">Datos Generales</h5>

    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Código de empleado</div>
            <div class="info-value">{{ $empleado->codigo ?? '-' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">DNI</div>
            <div class="info-value">{{ $empleado->DNI ?? '-' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">RTN</div>
            <div class="info-value">{{ $empleado->RTN ?? '-' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Sexo</div>
            <div class="info-value">{{ $empleado->sexo ?? '-' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Primer Nombre</div>
            <div class="info-value">{{ $empleado->primer_nombre ?? '-' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Segundo Nombre</div>
            <div class="info-value">{{ $empleado->segundo_nombre ?: '-' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Primer Apellido</div>
            <div class="info-value">{{ $empleado->primer_apellido ?? '-' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Segundo Apellido</div>
            <div class="info-value">{{ $empleado->segundo_apellido ?: '-' }}</div>
        </div>
    </div>

    {{-- INFORMACIÓN PERSONAL --}}
    <h5 class="section-title">Información Personal</h5>

    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Fecha de Nacimiento</div>
            <div class="info-value">{{ $fechaNacimiento }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Estado Civil</div>
            <div class="info-value">{{ $empleado->estado_civil ?? '-' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Nacionalidad</div>
            <div class="info-value">{{ $empleado->nacionalidad ?? '-' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Tipo de Sangre</div>
            <div class="info-value">{{ $empleado->tipo_sangre ?? '-' }}</div>
        </div>

        <div class="info-item info-half">
            <div class="info-label">Nivel Educativo</div>
            <div class="info-value">{{ $empleado->nivel_educativo ?? '-' }}</div>
        </div>
    </div>

    {{-- INFORMACIÓN DE CONTACTO --}}
    <h5 class="section-title">Información de Contacto</h5>

    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Teléfono Celular</div>
            <div class="info-value">{{ $empleado->telefono_celular ?? '-' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Teléfono Fijo</div>
            <div class="info-value">{{ $empleado->telefono_fijo ?: '-' }}</div>
        </div>

        <div class="info-item info-wide">
            <div class="info-label">Dirección de Domicilio</div>
            <div class="info-value">{{ $empleado->direccion_domicilio ?? '-' }}</div>
        </div>

        <div class="info-item info-wide">
            <div class="info-label">Referencia de Domicilio</div>
            <div class="info-value">{{ $empleado->referencia_domicilio ?? '-' }}</div>
        </div>
    </div>

    {{-- INFORMACIÓN LABORAL --}}
    <h5 class="section-title">Información Laboral</h5>

    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Puesto</div>
            <div class="info-value">{{ $empleado->puesto ?? '-' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Tipo</div>
            <div class="info-value">
                <span class="badge-status">{{ $empleado->tipo ?? '-' }}</span>
            </div>
        </div>

        <div class="info-item">
            <div class="info-label">Estado</div>
            <div class="info-value">
                <span class="badge-status">{{ ucfirst($empleado->estado_empleado ?? 'activo') }}</span>
            </div>
        </div>

        <div class="info-item">
            <div class="info-label">Salario Inicial</div>
            <div class="info-value">L. {{ number_format($empleado->salario_inicial ?? 0, 2) }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Fecha Nombramiento</div>
            <div class="info-value">{{ $empleado->fecha_nombramiento ?? '-' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Fecha Fin Contrato</div>
            <div class="info-value">{{ $empleado->fecha_fin_contrato ?: 'No aplica' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Depto. Administrativo</div>
            <div class="info-value">{{ $empleado->departamento->nombre ?? 'No asignado' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Depto. Funcional</div>
            <div class="info-value">{{ $empleado->departamentoFuncional->nombre ?? 'Sin asignación' }}</div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('empleados.funcion',$empleado->DNI) }}"
           class="btn-funcional">
            Cambiar asignación funcional
        </a>
    </div>

    {{-- CONTACTOS DE EMERGENCIA --}}
    <h5 class="section-title">Contactos de Emergencia</h5>

    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Contacto 1</div>
            <div class="info-value">{{ $empleado->nombre_contacto1 ?? '-' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Teléfono Contacto 1</div>
            <div class="info-value">{{ $empleado->telefono_contacto1 ?? '-' }}</div>
        </div>

        <div class="info-item info-half">
            <div class="info-label">Parentesco Contacto 1</div>
            <div class="info-value">{{ $empleado->parentezco_contacto1 ?? '-' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Contacto 2</div>
            <div class="info-value">{{ $empleado->nombre_contacto2 ?: '-' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Teléfono Contacto 2</div>
            <div class="info-value">{{ $empleado->telefono_contacto2 ?: '-' }}</div>
        </div>

        <div class="info-item info-half">
            <div class="info-label">Parentesco Contacto 2</div>
            <div class="info-value">{{ $empleado->parentezco_contacto2 ?: '-' }}</div>
        </div>
    </div>

    {{-- BENEFICIARIOS --}}
    <h5 class="section-title">Beneficiarios</h5>

    @if(count($beneficiarios) > 0)
        <div class="table-responsive beneficiarios-table">
            <table class="table table-bordered table-hover text-center mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Parentesco</th>
                        <th>Porcentaje</th>
                        <th>DNI</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($beneficiarios as $beneficiario)
                        <tr>
                            <td>{{ $beneficiario['nombre'] }}</td>
                            <td>{{ $beneficiario['parentezco'] ?: '-' }}</td>
                            <td><strong>{{ $beneficiario['porcentaje'] }}%</strong></td>
                            <td>{{ $beneficiario['dni'] ?: '-' }}</td>
                        </tr>
                    @endforeach

                    <tr>
                        <td colspan="2" class="text-end fw-bold">Total asignado</td>
                        <td class="fw-bold">{{ $totalBeneficiarios }}%</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-box">
            No se registraron beneficiarios para este empleado.
        </div>
    @endif

    {{-- AUDITORÍA --}}
    <h5 class="section-title">Auditoría del Registro</h5>

    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Registrado por:</div>
            <div class="info-value">{{ $empleado->usuario_crea ?? '-' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Última modificación por:</div>
            <div class="info-value">{{ $empleado->usuario_modifica ?: '-' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Fecha de registro:</div>
            <div class="info-value">{{ $empleado->created_at ? $empleado->created_at->format('d/m/Y h:i A') : '-' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Última Actualización</div>
            <div class="info-value">{{ $empleado->updated_at ? $empleado->updated_at->format('d/m/Y h:i A') : '-' }}</div>
        </div>
    </div>

    <div class="action-bar">
        <a href="{{ route('empleados.index') }}"
           class="btn btn-secondary">
            Volver
        </a>

        <a href="{{ route('empleados.verRegistro.imprimir', $empleado->DNI) }}"
           class="btn btn-primary-custom"
           target="_blank">
            Imprimir
        </a>
    </div>

</div>

@endsection