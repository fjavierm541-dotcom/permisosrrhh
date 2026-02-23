<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstadoPermisoSistema;

class EstadoPermisoSistemaSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            'Pendiente',
            'Aprobado',
            'Rechazado',
            'Vencido',
        ];

        foreach ($estados as $nombre) {

            EstadoPermisoSistema::updateOrCreate(
                ['nombre' => $nombre], // condición para buscar
                [] // 🔄 no actualiza nada extra porque solo tienes nombre
            );
        }
    }
}