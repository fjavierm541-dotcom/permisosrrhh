<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes_compensatorios', function (Blueprint $table) {
            $table->id();

            // Contexto
            $table->unsignedBigInteger('departamento_id');

            // Fecha trabajada
            $table->date('fecha_trabajada');

            // Días
            $table->integer('dias_sugeridos')->nullable();   // calculado por sistema
            $table->integer('dias_aprobados')->nullable();   // definido por RRHH

            // Información adicional
            $table->text('descripcion')->nullable();
            $table->string('documento_path')->nullable();

            // Estado
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])
                  ->default('pendiente');

            // Control de tardío
            $table->boolean('es_registro_tardio')->default(false);
            $table->text('justificacion')->nullable();

            // Auditoría
            $table->unsignedBigInteger('creado_por');
            $table->unsignedBigInteger('aprobado_por')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_compensatorios');
    }
};

