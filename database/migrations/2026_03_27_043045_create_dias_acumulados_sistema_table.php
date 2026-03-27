<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dias_acumulados_sistema', function (Blueprint $table) {
            $table->id();

            $table->string('dni_empleado');

            $table->integer('dias_vacacionales')->default(0);
            $table->integer('dias_compensatorios')->default(0);
            $table->decimal('horas_acumuladas', 8, 2)->default(0);

            $table->foreign('dni_empleado')
                  ->references('DNI')
                  ->on('empleados')
                  ->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dias_acumulados_sistema');
    }
};
