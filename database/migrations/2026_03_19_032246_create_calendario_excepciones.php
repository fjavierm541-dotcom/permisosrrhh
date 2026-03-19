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
        Schema::create('calendario_excepciones', function (Blueprint $table) {

    $table->id();

    $table->foreignId('calendario_dia_id')
        ->constrained('calendario_dias')
        ->cascadeOnDelete();

    $table->foreignId('departamento_id')
        ->constrained('departamentos_muni')
        ->cascadeOnDelete();

    $table->enum('tipo', ['trabaja']);

    $table->timestamps();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendario_excepciones');
    }
};
