<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class MovimientoPermisoSistema extends Model
{
    protected $table = 'movimientos_permisos_sistema';

    protected $fillable = [
        'dni_empleado',
        'periodo_id',
        'permiso_id',
        'categoria',
        'tipo_movimiento',
        'dias_afectados',
        'horas_afectadas',
        'descripcion'
    ];

    public function periodo()
    {
        return $this->belongsTo(PeriodoVacacionesSistema::class, 'periodo_id');
    }

    public function permiso()
    {
        return $this->belongsTo(PermisoSistema::class, 'permiso_id');
    }
}
