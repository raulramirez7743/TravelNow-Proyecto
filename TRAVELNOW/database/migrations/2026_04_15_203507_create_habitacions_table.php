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
    Schema::create('habitaciones', function (Blueprint $table) {
        $table->id();
        $table->string('tipo');
        $table->decimal('precio', 10, 2);
        $table->unsignedBigInteger('id_hotel');

        $table->timestamps(); // ⭐ IMPORTANTE
    });
}

public function down(): void
{
    Schema::dropIfExists('habitacions');
}
};
