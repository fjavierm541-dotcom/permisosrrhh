<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DepartamentoMuni;

class DepartamentosMuniSeeder extends Seeder
{
    public function run(): void
    {
        DepartamentoMuni::insert([

            ['codigo'=>'001','nombre'=>'Regidores','departamento_padre_id'=>null],
            ['codigo'=>'002','nombre'=>'Alcalde Municipal','departamento_padre_id'=>null],
            ['codigo'=>'003','nombre'=>'Auditoría Interna','departamento_padre_id'=>null],
            ['codigo'=>'004','nombre'=>'Secretaría Municipal','departamento_padre_id'=>null],
            ['codigo'=>'005','nombre'=>'Gerencia Financiera Administrativa','departamento_padre_id'=>null],
            ['codigo'=>'006','nombre'=>'Tesorería Municipal','departamento_padre_id'=>null],
            ['codigo'=>'007','nombre'=>'Contabilidad y Presupuesto','departamento_padre_id'=>null],
            ['codigo'=>'008','nombre'=>'Control Tributario','departamento_padre_id'=>null],
            ['codigo'=>'009','nombre'=>'Catastro','departamento_padre_id'=>null],
            ['codigo'=>'010','nombre'=>'Compras y Suministros','departamento_padre_id'=>null],
            ['codigo'=>'011','nombre'=>'Unidad Municipal de Informática (UMI)','departamento_padre_id'=>null],
            ['codigo'=>'012','nombre'=>'Justicia Municipal','departamento_padre_id'=>null],
            ['codigo'=>'013','nombre'=>'Unidad Municipal Ambiental (UMA)','departamento_padre_id'=>null],
            ['codigo'=>'014','nombre'=>'Unidad Técnica Municipal (UTM)','departamento_padre_id'=>null],
            ['codigo'=>'015','nombre'=>'Recursos Humanos','departamento_padre_id'=>null],
            ['codigo'=>'016','nombre'=>'Terminal Municipal de Transporte','departamento_padre_id'=>null],
            ['codigo'=>'017','nombre'=>'Ingeniería Municipal','departamento_padre_id'=>null],
            ['codigo'=>'018','nombre'=>'Desarrollo Comunitario','departamento_padre_id'=>null],
            ['codigo'=>'019','nombre'=>'Relaciones Públicas (RRHH)','departamento_padre_id'=>null],
            ['codigo'=>'020','nombre'=>'Bienes y Proveeduría','departamento_padre_id'=>null],
            ['codigo'=>'021','nombre'=>'Programa de la Niñez Adolescencia y Juventud','departamento_padre_id'=>null],
            ['codigo'=>'022','nombre'=>'Unidad Desarrollo Económico Local (UDEL)','departamento_padre_id'=>null],
            ['codigo'=>'023','nombre'=>'Oficina de la Mujer','departamento_padre_id'=>null],
            ['codigo'=>'024','nombre'=>'Centro de Atención Integral a la Niñez (CAIN)','departamento_padre_id'=>null],
            ['codigo'=>'025','nombre'=>'Departamento Legal Municipal','departamento_padre_id'=>null],
            ['codigo'=>'026','nombre'=>'Obras y Vías de Comunicación / Plantel','departamento_padre_id'=>null],
            ['codigo'=>'027','nombre'=>'Conciliación','departamento_padre_id'=>12],
            ['codigo'=>'028','nombre'=>'Alcaldes Auxiliares','departamento_padre_id'=>12],
            ['codigo'=>'029','nombre'=>'Escuela Taller','departamento_padre_id'=>null],
            ['codigo'=>'030','nombre'=>'Clínica Médica Municipal','departamento_padre_id'=>null],
            ['codigo'=>'031','nombre'=>'Oficina Municipal de Atención a Personas con Discapacidad (OMAPED)','departamento_padre_id'=>null],
            ['codigo'=>'032','nombre'=>'Unidad de Higiene y Aseo','departamento_padre_id'=>null],


        ]); 
    }
}