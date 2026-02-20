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
        Schema::create('calendario_institucional_sistema', function (Blueprint $table) {
    $table->id();
    $table->date('fecha');
    $table->string('tipo'); // feriado | institucional | extraordinario
    $table->string('descripcion')->nullable();
    $table->integer('anio');
    $table->boolean('activo')->default(true);
    $table->timestamps();
});

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendario_institucional_sistema');
    }
};
