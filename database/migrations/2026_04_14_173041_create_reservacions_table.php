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
        Schema::dropIfExists('reservacions'); // Limpiar tabla corrupta del intento anterior

        Schema::create('reservacions', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->foreignId('id_usuario')->references('id_usuario')->on('usuarios');
            $table->foreignId('id_habitacion')->constrained('habitacions');
            $table->foreignId('id_vuelo')->nullable()->constrained('vuelos');
            $table->timestamps(); // ✅ AGREGADO
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservacions');
    }
};
