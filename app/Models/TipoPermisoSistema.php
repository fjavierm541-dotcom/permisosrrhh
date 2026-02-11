<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoPermisoSistema extends Model
{
    protected $table = 'tipos_permiso_sistema';

    protected $fillable = [
        'nombre',
        'resta_dias',
        'resta_horas',
        'requiere_documento',
        'activo'
    ];

    protected $casts = [
        'resta_dias' => 'boolean',
        'resta_horas' => 'boolean',
        'requiere_documento' => 'boolean',
        'activo' => 'boolean',
    ];

    public function permisos()
    {
        return $this->hasMany(PermisoSistema::class, 'tipo_permiso_id');
    }
}
