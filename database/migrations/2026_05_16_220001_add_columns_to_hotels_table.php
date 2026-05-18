<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FIX: Agrega columnas faltantes a la tabla 'hotels'.
 * El Admin envía imagen, descripcion, precio_noche pero no existían en la tabla.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            if (!Schema::hasColumn('hotels', 'imagen')) {
                $table->string('imagen')->nullable()->after('estrellas');
            }
            if (!Schema::hasColumn('hotels', 'descripcion')) {
                $table->text('descripcion')->nullable()->after('imagen');
            }
            if (!Schema::hasColumn('hotels', 'precio_noche')) {
                $table->decimal('precio_noche', 10, 2)->nullable()->after('descripcion');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn(['imagen', 'descripcion', 'precio_noche']);
        });
    }
};
