<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodoVacacionesSistema extends Model
{
    use HasFactory;

    protected $table = 'periodos_vacaciones_sistema';

    protected $fillable = [
        'dni_empleado',
        'anio_laboral',
        'dias_otorgados',
        'dias_usados',
        'fecha_inicio_periodo',
        'fecha_vencimiento'
    ];

    protected $casts = [
        'fecha_inicio_periodo' => 'date',
        'fecha_vencimiento' => 'date'
    ];
}
