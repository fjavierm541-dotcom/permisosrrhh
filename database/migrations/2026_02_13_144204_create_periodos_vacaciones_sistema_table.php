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
    Schema::create('periodos_vacaciones_sistema', function (Blueprint $table) {
        $table->id();

        // Relación con empleado (usamos DNI porque así trabajas en tu sistema)
        $table->string('dni_empleado');

        // Año laboral que generó el período
        $table->year('anio_laboral');

        // Días otorgados ese año
        $table->integer('dias_otorgados');

        // Días que todavía están disponibles
        $table->integer('dias_restantes');

        // Fecha en que comenzó a contar ese período
        $table->date('fecha_inicio_periodo');

        // Fecha en que debería vencer (inicio + 2 años)
        $table->date('fecha_vencimiento');

        // Si RRHH autoriza extensión
        $table->date('extension_hasta')->nullable();

        // Estado del período
        $table->enum('estado', ['activo', 'vencido', 'extendido'])->default('activo');

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodos_vacaciones_sistema');
    }
    

    
    
};
