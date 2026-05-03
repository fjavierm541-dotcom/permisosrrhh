<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoraAcumuladaSistema extends Model
{
    protected $table = 'horas_acumuladas_sistema';

    protected $fillable = [
        'dni_empleado',
        'horas_otorgadas',
        'horas_usadas',
        'fecha_origen',
        'fecha_vencimiento',
        'estado',
        'origen',
        'referencia_id',
    ];

    protected $casts = [
        'horas_otorgadas' => 'decimal:2',
        'horas_usadas' => 'decimal:2',
        'fecha_origen' => 'date',
        'fecha_vencimiento' => 'date',
    ];

    public function getHorasDisponiblesAttribute()
    {
        return $this->horas_otorgadas - $this->horas_usadas;
    }
}