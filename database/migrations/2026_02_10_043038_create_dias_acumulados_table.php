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
        Schema::create('dias_acumulados', function (Blueprint $table) {
    $table->id();

    $table->foreignId('empleado_id')
        ->unique()
        ->constrained('empleados');

    $table->decimal('dias_vacaciones', 5, 2)->default(0);
    $table->decimal('dias_compensatorios', 5, 2)->default(0);
    $table->integer('horas_acumuladas')->default(0);

    $table->timestamp('updated_at')->nullable();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dias_acumulados');
    }
};
