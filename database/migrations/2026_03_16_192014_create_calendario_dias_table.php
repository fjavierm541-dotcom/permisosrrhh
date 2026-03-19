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

    $table->string('titulo', 150);

    $table->date('fecha_inicio');

    $table->date('fecha_fin')->nullable();

    $table->enum('origen', ['nacional','local']);

    $table->enum('tipo_afectacion', [
        'no_laborable',
        'descuento'
    ])->default('no_laborable');

    $table->text('descripcion');

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
