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
        'dias_restantes',
        'fecha_inicio_periodo',
        'fecha_vencimiento',
        'extension_hasta',
        'estado'
    ];

    protected $casts = [
        'fecha_inicio_periodo' => 'date',
        'fecha_vencimiento' => 'date',
        'extension_hasta' => 'date'
    ];

    /**
     * 🔥 Calcular automáticamente días restantes
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($periodo) {

            // Si dias_usados es null lo convertimos en 0
            $usados = $periodo->dias_usados ?? 0;
            $otorgados = $periodo->dias_otorgados ?? 0;

            $periodo->dias_restantes = $otorgados - $usados;

        });
    }
}