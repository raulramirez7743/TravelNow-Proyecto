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
        // 1. Creamos la tabla 'users' (el estándar que buscan tus controladores)
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Esto crea la columna 'id' que usarán las reservaciones
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            // 2. Agregamos el campo rol para diferenciar Admin de Cliente
            $table->enum('rol', ['admin', 'cliente'])->default('cliente');
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};