<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol_id',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /* ======================
       RELACIONES
    ====================== */

    public function role()
{
    return $this->belongsTo(Role::class);
}

public function empleado()
{
    return $this->hasOne(Empleado::class);
}


    public function permisos()
{
    return $this->hasMany(Permiso::class);
}

    public function diasAcumulados()
    {
        return $this->hasOne(DiasAcumulados::class);
    }
}
