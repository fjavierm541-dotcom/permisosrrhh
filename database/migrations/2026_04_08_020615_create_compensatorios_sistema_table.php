<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compensatorios_sistema', function (Blueprint $table) {
            $table->id();

            $table->string('dni_empleado');

            $table->integer('dias_otorgados');
            $table->integer('dias_disponibles');

            $table->date('fecha_origen');
            $table->date('fecha_vencimiento');

            $table->enum('estado', ['activo', 'agotado', 'vencido'])
                  ->default('activo');

            $table->string('origen'); // fin_semana, feriado, etc

            $table->unsignedBigInteger('referencia_id'); // solicitud

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compensatorios_sistema');
    }
};