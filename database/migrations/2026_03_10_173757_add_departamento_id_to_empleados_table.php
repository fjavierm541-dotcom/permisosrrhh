<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empleados', function (Blueprint $table) {

            $table->foreignId('departamento_id')
                ->nullable()
                ->after('salario_inicial')
                ->constrained('departamentos_muni')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('empleados', function (Blueprint $table) {

            $table->dropForeign(['departamento_id']);
            $table->dropColumn('departamento_id');

        });
    }
};
