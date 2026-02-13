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
    Schema::table('periodos_vacaciones_sistema', function (Blueprint $table) {
        $table->integer('dias_usados')->default(0)->after('dias_otorgados');
    });
}

public function down(): void
{
    Schema::table('periodos_vacaciones_sistema', function (Blueprint $table) {
        $table->dropColumn('dias_usados');
    });
}

};
