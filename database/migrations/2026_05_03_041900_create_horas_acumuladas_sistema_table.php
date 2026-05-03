<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horas_acumuladas_sistema', function (Blueprint $table) {
            $table->id();

            $table->string('dni_empleado');

            $table->decimal('horas_otorgadas', 8, 2)->default(0);
            $table->decimal('horas_usadas', 8, 2)->default(0);

            $table->date('fecha_origen')->nullable();
            $table->date('fecha_vencimiento')->nullable();

            $table->enum('estado', ['activo', 'agotado', 'vencido'])->default('activo');

            $table->string('origen')->nullable(); 
            // compensatorio, vacaciones

            $table->unsignedBigInteger('referencia_id')->nullable(); 
            // id del compensatorio o periodo de vacaciones

            $table->timestamps();

            $table->foreign('dni_empleado')
                ->references('DNI')
                ->on('empleados')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horas_acumuladas_sistema');
    }
};