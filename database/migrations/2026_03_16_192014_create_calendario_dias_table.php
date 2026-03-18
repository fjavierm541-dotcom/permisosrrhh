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
        Schema::create('calendario_dias', function (Blueprint $table) {

    $table->id();

    $table->string('titulo');

    $table->date('fecha_inicio');

    $table->date('fecha_fin')->nullable();

    $table->enum('origen', [
        'nacional',
        'local'
    ])->default('local');

    $table->text('descripcion')->nullable();

    $table->timestamps();

});



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendario_dias');
    }
};
