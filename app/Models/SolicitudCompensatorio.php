<?php

namespace App\Models;
use App\Models\DepartamentoMuni;
use App\Models\SolicitudCompensatorioEmpleado;
use Illuminate\Database\Eloquent\Model;

class SolicitudCompensatorio extends Model
{
    protected $table = 'solicitudes_compensatorios';

    protected $fillable = [
        'departamento_id',
        'fecha_trabajada',
        'dias_sugeridos',
        'dias_aprobados',
        'descripcion',
        'documento_path',
        'estado',
        'es_registro_tardio',
        'justificacion',
        'creado_por',
        'aprobado_por'

        
    ];


public function departamento()
{
    return $this->belongsTo(DepartamentoMuni::class, 'departamento_id');
}

public function empleados()
{
    return $this->hasMany(SolicitudCompensatorioEmpleado::class, 'solicitud_id');
}

    
    
}