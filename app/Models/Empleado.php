<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';

    protected $primaryKey = 'DNI';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = true;

    public function permisosSistema()
    {
        return $this->hasMany(PermisoSistema::class, 'dni_empleado', 'DNI');
    }

    public function diasAcumuladosSistema()
    {
        return $this->hasOne(DiasAcumuladosSistema::class, 'dni_empleado', 'DNI');
    }
}
