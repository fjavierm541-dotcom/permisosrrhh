<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compensatorio extends Model
{
    protected $table = 'compensatorios_sistema';

    protected $fillable = [
        'dni_empleado',
        'dias_otorgados',
        'dias_disponibles',
        'fecha_origen',
        'fecha_vencimiento',
        'estado',
        'origen',
        'referencia_id'
    ];
}