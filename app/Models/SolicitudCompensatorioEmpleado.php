<?php

namespace App\Models;
use App\Models\Empleado;

use Illuminate\Database\Eloquent\Model;

class SolicitudCompensatorioEmpleado extends Model
{
    protected $table = 'solicitud_compensatorio_empleados';

    protected $fillable = [
        'solicitud_id',
        'dni_empleado'
    ];

    public function solicitud()
    {
        return $this->belongsTo(SolicitudCompensatorio::class, 'solicitud_id');
    }

    public function empleado()
{
    return $this->belongsTo(Empleado::class, 'dni_empleado', 'DNI');
}
}