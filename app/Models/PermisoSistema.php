<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermisoSistema extends Model
{
    protected $table = 'permisos_sistema';

    protected $fillable = [
    'dni_empleado',
    'modalidad',
    'tipo_permiso_id',
    'estado_permiso_id',
    'fecha_inicio',
    'fecha_fin',
    'horas',
    'motivo',
    'documento'
];


    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'dni_empleado', 'DNI');
    }

    public function tipo()
    {
        return $this->belongsTo(TipoPermisoSistema::class, 'tipo_permiso_id');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoPermisoSistema::class, 'estado_permiso_id');
    }


    public function rechazar($id)
{
    $permiso = PermisoSistema::findOrFail($id);

    // Estado 3 = Rechazado
    $permiso->estado_permiso_id = 3;
    $permiso->save();

    return back()->with('success', 'Permiso rechazado correctamente');
}

}
