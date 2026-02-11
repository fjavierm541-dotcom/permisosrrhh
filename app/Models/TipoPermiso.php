<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPermiso extends Model
{
    use HasFactory;

    protected $table = 'tipos_permiso';

    protected $fillable = [
        'nombre',
        'resta_dias',
        'resta_horas',
        'requiere_documento',
        'activo'
    ];

    public function permisos()
    {
        return $this->hasMany(Permiso::class);
    }
}

