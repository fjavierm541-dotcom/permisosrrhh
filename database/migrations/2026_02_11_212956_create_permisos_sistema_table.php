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
    Schema::create('permisos_sistema', function (Blueprint $table) {
        $table->id();
        $table->string('dni_empleado');
        $table->foreignId('tipo_permiso_id')->constrained('tipos_permiso_sistema');
        $table->foreignId('estado_permiso_id')->constrained('estados_permiso_sistema');
        $table->date('fecha_inicio');
        $table->date('fecha_fin')->nullable();
        $table->integer('horas')->default(0);
        $table->text('motivo')->nullable();
        $table->string('documento')->nullable();
        $table->timestamps();
    });
}

};
