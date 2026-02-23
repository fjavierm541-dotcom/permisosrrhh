<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoPermisoSistema;

class TipoPermisoSistemaSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [

            [
                'nombre' => 'Vacaciones',
                'resta_dias' => 1,
                'resta_horas' => 0,
                'requiere_documento' => 0,
                'activo' => 1,
            ],

            [
                'nombre' => 'Compensatorio',
                'resta_dias' => 0,
                'resta_horas' => 0,
                'requiere_documento' => 0,
                'activo' => 1,
            ],

            [
                'nombre' => 'Permiso Personal',
                'resta_dias' => 0,
                'resta_horas' => 1,
                'requiere_documento' => 0,
                'activo' => 1,
            ],

            [
                'nombre' => 'Cita Médica',
                'resta_dias' => 0,
                'resta_horas' => 1,
                'requiere_documento' => 0,
                'activo' => 1,
            ],

            [
                'nombre' => 'Permiso por Duelo',
                'resta_dias' => 0,
                'resta_horas' => 0,
                'requiere_documento' => 1,
                'activo' => 1,
            ],

            [
                'nombre' => 'Permiso Institucional',
                'resta_dias' => 0,
                'resta_horas' => 0,
                'requiere_documento' => 0,
                'activo' => 1,
            ],

            [
                'nombre' => 'Trabajo en Día Inhábil',
                'resta_dias' => 0,
                'resta_horas' => 0,
                'requiere_documento' => 0,
                'activo' => 1,
            ],

            [
                'nombre' => 'Otros',
                'resta_dias' => 0,
                'resta_horas' => 0,
                'requiere_documento' => 0,
                'activo' => 1,
            ],
        ];

        foreach ($tipos as $tipo) {

            TipoPermisoSistema::updateOrCreate(
                ['nombre' => $tipo['nombre']],
                $tipo
            );
        }
    }
}