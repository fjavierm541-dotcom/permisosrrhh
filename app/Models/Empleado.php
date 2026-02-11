<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function diasAcumulados()
    {
        return $this->hasOne(DiasAcumulados::class);
    }

    public function permisos()
    {
        return $this->hasMany(Permiso::class);
    }
}
