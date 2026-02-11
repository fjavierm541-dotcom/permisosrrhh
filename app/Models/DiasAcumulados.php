<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiasAcumulados extends Model
{
    use HasFactory;

    protected $table = 'dias_acumulados';

    protected $fillable = [
        'empleado_id',
        'dias_vacacionales',
        'dias_compensatorios',
        'horas_acumuladas',
    ];

    public $timestamps = false; // porque solo tiene updated_at

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
