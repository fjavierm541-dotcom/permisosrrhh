<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarioDia extends Model
{

    protected $table = 'calendario_dias';

    protected $fillable = [
        'titulo',
        'fecha_inicio',
        'fecha_fin',
        'origen',
        'descripcion'
    ];

}