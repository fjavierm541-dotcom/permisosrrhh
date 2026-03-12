<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartamentoMuni extends Model
{
    protected $table = 'departamentos_muni';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'departamento_padre_id',
        'jefe_dni',
        'activo'
    ];

    public function padre()
    {
        return $this->belongsTo(DepartamentoMuni::class, 'departamento_padre_id');
    }

    public function hijos()
    {
        return $this->hasMany(DepartamentoMuni::class, 'departamento_padre_id');
    }

    public function empleadosAdministrativos()
    {
        return $this->hasMany(Empleado::class,'departamento_id');
    }

    public function empleadosFuncionales()
{
    return $this->hasMany(\App\Models\Empleado::class,'departamento_funcional_id');
}

        public function jefe()
    {
        return $this->belongsTo(\App\Models\Empleado::class,'jefe_dni','DNI');
    }


}