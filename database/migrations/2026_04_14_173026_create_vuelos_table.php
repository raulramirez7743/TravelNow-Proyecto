<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vuelos', function (Blueprint $table) {
            $table->id();
            $table->string('aerolinea');
            $table->string('origen');
            $table->string('destino_vuelo')->nullable();
            $table->date('fecha_salida');
            $table->decimal('precio', 10, 2);
            $table->integer('asientos')->default(100);
            $table->string('imagen')->nullable();
            $table->foreignId('id_destino')->constrained('destinos');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vuelos');
    }
};
