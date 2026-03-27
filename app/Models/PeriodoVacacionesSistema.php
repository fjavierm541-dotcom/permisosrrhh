<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PeriodoVacacionesSistema extends Model
{
    use HasFactory;

    protected $table = 'periodos_vacaciones_sistema'; 

    protected $fillable = [
        'dni_empleado',
        'anio_laboral',
        'dias_otorgados',
        'dias_usados',
        'dias_restantes',
        'fecha_inicio_periodo',
        'fecha_vencimiento',
        'extension_hasta',
        'estado',
        'motivo_extension',     
        'documento_extension'   
    ];

    protected $casts = [
        'fecha_inicio_periodo' => 'date',
        'fecha_vencimiento' => 'date',
        'extension_hasta' => 'date'
    ];

    protected static function boot()
{
    parent::boot();

    static::saving(function ($periodo) {

        $usados = $periodo->dias_usados ?? 0;
        $otorgados = $periodo->dias_otorgados ?? 0;

        $restantes = $otorgados - $usados;

        // 🔥 evitar negativos
        $periodo->dias_restantes = $restantes >= 0 ? $restantes : 0;
    });
    
}

public function empleado()
{
    return $this->belongsTo(Empleado::class, 'dni_empleado', 'DNI');
}
}