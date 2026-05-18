<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FIX: Agrega columnas faltantes a la tabla 'vuelos'.
 * El Admin envía destino_vuelo, asientos, imagen pero no existían en la tabla.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vuelos', function (Blueprint $table) {
            if (!Schema::hasColumn('vuelos', 'destino_vuelo')) {
                $table->string('destino_vuelo')->nullable()->after('origen');
            }
            if (!Schema::hasColumn('vuelos', 'asientos')) {
                $table->integer('asientos')->default(100)->after('precio');
            }
            if (!Schema::hasColumn('vuelos', 'imagen')) {
                $table->string('imagen')->nullable()->after('asientos');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vuelos', function (Blueprint $table) {
            $table->dropColumn(['destino_vuelo', 'asientos', 'imagen']);
        });
    }
};
