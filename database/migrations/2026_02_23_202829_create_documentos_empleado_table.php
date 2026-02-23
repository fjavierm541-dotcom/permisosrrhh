<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

public function up()
{
    Schema::create('documentos_empleado', function (Blueprint $table) {
        $table->id();
        $table->string('dni_empleado');
        $table->string('tipo_documento');
        $table->string('ruta_archivo');
        $table->timestamps();

        $table->foreign('dni_empleado')
            ->references('DNI')
            ->on('empleados')
            ->onDelete('cascade');
    });
}

public function down()
{
    Schema::dropIfExists('documentos_empleado');
}
    
};
