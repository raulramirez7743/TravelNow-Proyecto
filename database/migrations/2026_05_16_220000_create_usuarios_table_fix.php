<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FIX: La migración original fue marcada como ejecutada pero la tabla nunca se creó.
 * Esta migración crea la tabla 'usuarios' que el modelo y controllers esperan.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('usuarios')) {
            Schema::create('usuarios', function (Blueprint $table) {
                $table->id('id_usuario');
                $table->string('nombre', 100);
                $table->string('correo', 100)->unique();
                $table->string('password');
                $table->string('telefono', 15)->nullable();
                $table->enum('rol', ['admin', 'staff'])->default('admin');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
