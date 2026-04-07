<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitud_compensatorio_empleados', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('solicitud_id');
            $table->string('dni_empleado');

            $table->timestamps();

            // Relaciones (recomendado después)
            // $table->foreign('solicitud_id')->references('id')->on('solicitudes_compensatorios')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitud_compensatorio_empleados');
    }
};