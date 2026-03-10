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
    public function empleados()
    {
    return $this->hasMany(\App\Models\Empleado::class, 'departamento_id', 'id');
    }

}