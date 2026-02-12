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
    Schema::table('permisos_sistema', function (Blueprint $table) {
        $table->enum('modalidad', [
            'horas',
            'medio_dia',
            'un_dia',
            'varios_dias'
        ])->after('dni_empleado');
    });
}

public function down()
{
    Schema::table('permisos_sistema', function (Blueprint $table) {
        $table->dropColumn('modalidad');
    });
}

};
