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
        Schema::create('permisos', function (Blueprint $table) {
    $table->id();

    $table->foreignId('empleado_id')->constrained('empleados');
    $table->foreignId('tipo_permiso_id')->constrained('tipos_permiso');
    $table->foreignId('estado_permiso_id')->constrained('estados_permiso');

    $table->date('fecha_inicio');
    $table->date('fecha_fin')->nullable();
    $table->integer('horas')->nullable();
    $table->text('motivo')->nullable();
    $table->string('documento')->nullable();

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permisos');
    }
};
