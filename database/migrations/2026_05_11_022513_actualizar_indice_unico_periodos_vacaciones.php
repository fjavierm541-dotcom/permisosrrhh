<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('periodos_vacaciones_sistema', function (Blueprint $table) {
            $table->unique(
                ['dni_empleado', 'tipo_periodo', 'numero_periodo'],
                'periodo_unico_empleado_tipo_numero'
            );
        });
    }

    public function down(): void
    {
        Schema::table('periodos_vacaciones_sistema', function (Blueprint $table) {
            $table->dropUnique('periodo_unico_empleado_tipo_numero');
        });
    }
};