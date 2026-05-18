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
        Schema::create('reservacions', function (Blueprint $table) {
            $table->id(); 
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            
            // 1. Cambiamos 'usuarios' por 'users' (el nombre estándar de Laravel)
            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
            
            // 2. Cambiamos 'habitacions' por 'habitaciones' (como la creamos en SQL)
            $table->unsignedBigInteger('id_habitacion');
            $table->foreign('id_habitacion')->references('id')->on('habitacions')->onDelete('cascade');
            
            // 3. El de vuelos está bien si la tabla se llama 'vuelos'
            $table->unsignedBigInteger('id_vuelo');
            $table->foreign('id_vuelo')->references('id')->on('vuelos')->onDelete('cascade');
            
            $table->timestamps();
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
