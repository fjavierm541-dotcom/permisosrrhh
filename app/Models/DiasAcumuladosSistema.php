<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiasAcumuladosSistema extends Model
{
    protected $table = 'dias_acumulados_sistema';

    protected $fillable = [
        'dni_empleado',
        'dias_vacacionales',
        'dias_compensatorios',
        'horas_acumuladas'
    ];

    public $timestamps = false;

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'dni_empleado', 'DNI');
    }
}
