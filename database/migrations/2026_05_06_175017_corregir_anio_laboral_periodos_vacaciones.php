<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE periodos_vacaciones_sistema MODIFY anio_laboral INT NOT NULL");

        Schema::table('periodos_vacaciones_sistema', function (Blueprint $table) {
            $table->unique(['dni_empleado', 'anio_laboral'], 'periodo_unico_empleado_anio');
        });
    }

    public function down(): void
    {
        Schema::table('periodos_vacaciones_sistema', function (Blueprint $table) {
            $table->dropUnique('periodo_unico_empleado_anio');
        });

        DB::statement("ALTER TABLE periodos_vacaciones_sistema MODIFY anio_laboral YEAR NOT NULL");
    }
};