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
    Schema::create('dias_acumulados_sistema', function (Blueprint $table) {
        $table->id();
        $table->string('dni_empleado');
        $table->integer('dias_vacacionales')->default(0);
        $table->integer('dias_compensatorios')->default(0);
        $table->integer('horas_acumuladas')->default(0);
        $table->timestamp('updated_at')->nullable();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dias_acumulados_sistema');
    }
};
