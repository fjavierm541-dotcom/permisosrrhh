<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoPermiso extends Model
{
    use HasFactory;

    protected $table = 'estados_permiso';

    protected $fillable = [
        'nombre',
    ];

    public function permisos()
    {
        return $this->hasMany(Permiso::class, 'estado_permiso_id');
    }
}
