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
        'activo',
    ];

    protected $casts = [
        'resta_dias' => 'boolean',
        'resta_horas' => 'boolean',
        'requiere_documento' => 'boolean',
        'activo' => 'boolean',
    ];

    public function permisos()
    {
        return $this->hasMany(Permiso::class, 'tipo_permiso_id');
    }
}
