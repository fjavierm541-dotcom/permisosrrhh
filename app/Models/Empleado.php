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

    protected $fillable = [
        "codigo",
        "primer_nombre",
        "segundo_nombre",
        "primer_apellido",
        "segundo_apellido",
        "dia_nacimiento",
        "mes_nacimiento",
        "anio_nacimiento",
        "sexo",
        "DNI",
        "RTN",
        "estado_civil",
        "nacionalidad",
        "tipo_sangre",
        "direccion_domicilio",
        "referencia_domicilio",
        "telefono_celular",
        "telefono_fijo",
        "nivel_educativo",
        "nombre_contacto1",
        "telefono_contacto1",
        "parentezco_contacto1",
        "nombre_contacto2",
        "telefono_contacto2",
        "parentezco_contacto2",
        "nombre_beneficiario1",
        "porcentaje_beneficiario1",
        "parentezco_beneficiario1",
        "DNI_beneficiario1",
        "nombre_beneficiario2",
        "porcentaje_beneficiario2",
        "parentezco_beneficiario2",
        "DNI_beneficiario2",
        "nombre_beneficiario3",
        "porcentaje_beneficiario3",
        "parentezco_beneficiario3",
        "DNI_beneficiario3",
        "nombre_beneficiario4",
        "porcentaje_beneficiario4",
        "parentezco_beneficiario4",
        "DNI_beneficiario4",
        "nombre_beneficiario5",
        "porcentaje_beneficiario5",
        "parentezco_beneficiario5",
        "DNI_beneficiario5",
        "nombre_beneficiario6",
        "porcentaje_beneficiario6",
        "parentezco_beneficiario6",
        "DNI_beneficiario6",
        "nombre_beneficiario7",
        "porcentaje_beneficiario7",
        "parentezco_beneficiario7",
        "DNI_beneficiario7",
        "foto",
        "firma",
        "huella",
        "puesto",
        "fecha_nombramiento",
        "tipo",
        "salario_inicial",
        "usuario_crea",
        "usuario_modifica",
    ];

    public function permisosSistema()
    {
        return $this->hasMany(PermisoSistema::class, 'dni_empleado', 'DNI');
    }

    public function diasAcumuladosSistema()
    {
        return $this->hasOne(DiasAcumuladosSistema::class, 'dni_empleado', 'DNI');
    }

    public function periodosVacaciones()
    {
        return $this->hasMany(PeriodoVacacionesSistema::class, 'dni_empleado', 'DNI');
    }
    public function documentos()
    {
    return $this->hasMany(DocumentoEmpleado::class, 'dni_empleado', 'DNI');
    }
}