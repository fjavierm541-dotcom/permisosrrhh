<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoEmpleado extends Model
{
    protected $table = 'documentos_empleado';

    protected $fillable = [
        'dni_empleado',
        'tipo_documento',
        'ruta_archivo'
    ];
}
