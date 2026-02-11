<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('tipos_permiso_sistema', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->boolean('resta_dias')->default(false);
        $table->boolean('resta_horas')->default(false);
        $table->boolean('requiere_documento')->default(false);
        $table->boolean('activo')->default(true);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_permiso_sistema');
    }
};
