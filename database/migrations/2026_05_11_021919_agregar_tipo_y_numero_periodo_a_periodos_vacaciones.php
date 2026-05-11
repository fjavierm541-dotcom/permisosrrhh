<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('periodos_vacaciones_sistema', function (Blueprint $table) {
            $table->string('tipo_periodo')->default('anual')->after('anio_laboral');
            $table->integer('numero_periodo')->nullable()->after('tipo_periodo');
        });
    }

    public function down(): void
    {
        Schema::table('periodos_vacaciones_sistema', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_periodo',
                'numero_periodo',
            ]);
        });
    }
};