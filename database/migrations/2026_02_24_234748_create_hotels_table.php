<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->tinyInteger('estrellas')->default(3);
            $table->string('imagen')->nullable();
            $table->text('descripcion')->nullable();
            $table->decimal('precio_noche', 10, 2)->nullable();
            $table->foreignId('id_destino')->constrained('destinos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
