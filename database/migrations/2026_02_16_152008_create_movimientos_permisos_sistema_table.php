<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_permisos_sistema', function (Blueprint $table) {

            $table->id();

            $table->string('dni_empleado');

            $table->foreignId('periodo_id')
                  ->nullable()
                  ->constrained('periodos_vacaciones_sistema')
                  ->nullOnDelete();

            $table->foreignId('permiso_id')
                  ->nullable()
                  ->constrained('permisos_sistema')
                  ->nullOnDelete();

            $table->string('categoria');
            // vacaciones | compensatorio | horas

            $table->string('tipo_movimiento');
            // generado | descuento | vencido | extension | ajuste_manual

            $table->integer('dias_afectados')->default(0);
            $table->integer('horas_afectadas')->default(0);

            $table->text('descripcion')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_permisos_sistema');
    }
};

