<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';

    protected $fillable = [
        'codigo_empleado',
        'nombres',
        'apellidos',
        'puesto',
        'departamento',
        'fecha_ingreso',
        'estado',
        'user_id'
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function diasAcumulados()
    {
        return $this->hasOne(DiasAcumulados::class, 'empleado_id');
    }

    public function permisos()
    {
        return $this->hasMany(Permiso::class, 'empleado_id');
    }
}
