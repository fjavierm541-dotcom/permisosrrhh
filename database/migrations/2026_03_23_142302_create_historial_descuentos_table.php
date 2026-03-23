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
        Schema::create('historial_descuentos', function (Blueprint $table) {

    $table->id();

    $table->string('dni_empleado');
    $table->date('fecha');

    $table->foreignId('calendario_dia_id')->nullable();

    $table->string('tipo'); // calendario / permiso

    $table->timestamps();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_descuentos');
    }
};
