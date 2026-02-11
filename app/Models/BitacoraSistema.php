<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BitacoraSistema extends Model
{
    protected $table = 'bitacora_sistema';

    protected $fillable = [
        'dni_empleado',
        'accion',
        'descripcion',
        'usuario'
    ];
}
