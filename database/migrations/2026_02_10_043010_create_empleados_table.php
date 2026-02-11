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
        Schema::create('empleados', function (Blueprint $table) {
    $table->id();
    $table->string('codigo_empleado', 20)->unique()->nullable();
    $table->string('nombres');
    $table->string('apellidos');
    $table->string('puesto')->nullable();
    $table->string('departamento')->nullable();
    $table->date('fecha_ingreso')->nullable();
    $table->enum('estado', ['activo', 'inactivo'])->default('activo');

    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
