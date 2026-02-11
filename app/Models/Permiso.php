<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;

    protected $table = 'permisos';

    protected $fillable = [
        'empleado_id',
        'tipo_permiso_id',
        'estado_permiso_id',
        'fecha_inicio',
        'fecha_fin',
        'horas',
        'motivo',
        'documento',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function tipoPermiso()
    {
        return $this->belongsTo(TipoPermiso::class, 'tipo_permiso_id');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoPermiso::class, 'estado_permiso_id');
    }
}
