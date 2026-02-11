<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoPermisoSistema extends Model
{
    protected $table = 'estados_permiso_sistema';

    protected $fillable = [
        'nombre'
    ];

    public function permisos()
    {
        return $this->hasMany(PermisoSistema::class, 'estado_permiso_id');
    }
}
