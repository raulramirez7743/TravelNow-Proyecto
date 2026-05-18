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
      Schema::create('vuelos', function (Blueprint $table) {
    $table->id();
    $table->string('aerolinea');
    $table->string('origen');
    $table->date('fecha_salida');
    $table->decimal('precio', 10, 2);
    $table->foreignId('id_destino')->constrained('destinos');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vuelos');
    }
};
