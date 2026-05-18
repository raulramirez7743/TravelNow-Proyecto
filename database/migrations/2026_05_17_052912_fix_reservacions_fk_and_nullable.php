<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Drop the wrong FK that points to `users` (non-existent table)
        //    and the strict NOT NULL FKs for habitacion/vuelo
        DB::statement('ALTER TABLE reservacions DROP FOREIGN KEY reservacions_id_usuario_foreign');
        DB::statement('ALTER TABLE reservacions DROP FOREIGN KEY reservacions_id_habitacion_foreign');
        DB::statement('ALTER TABLE reservacions DROP FOREIGN KEY reservacions_id_vuelo_foreign');

        // 2. Make id_habitacion and id_vuelo nullable
        DB::statement('ALTER TABLE reservacions MODIFY id_habitacion BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE reservacions MODIFY id_vuelo BIGINT UNSIGNED NULL');

        // 3. Re-add FKs pointing to the CORRECT tables
        DB::statement('ALTER TABLE reservacions ADD CONSTRAINT reservacions_id_usuario_foreign FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE SET NULL');
        DB::statement('ALTER TABLE reservacions ADD CONSTRAINT reservacions_id_habitacion_foreign FOREIGN KEY (id_habitacion) REFERENCES habitacions(id) ON DELETE SET NULL');
        DB::statement('ALTER TABLE reservacions ADD CONSTRAINT reservacions_id_vuelo_foreign FOREIGN KEY (id_vuelo) REFERENCES vuelos(id) ON DELETE SET NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE reservacions DROP FOREIGN KEY reservacions_id_usuario_foreign');
        DB::statement('ALTER TABLE reservacions DROP FOREIGN KEY reservacions_id_habitacion_foreign');
        DB::statement('ALTER TABLE reservacions DROP FOREIGN KEY reservacions_id_vuelo_foreign');

        DB::statement('ALTER TABLE reservacions MODIFY id_habitacion BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE reservacions MODIFY id_vuelo BIGINT UNSIGNED NOT NULL');

        DB::statement('ALTER TABLE reservacions ADD CONSTRAINT reservacions_id_usuario_foreign FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE');
        DB::statement('ALTER TABLE reservacions ADD CONSTRAINT reservacions_id_habitacion_foreign FOREIGN KEY (id_habitacion) REFERENCES habitacions(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE reservacions ADD CONSTRAINT reservacions_id_vuelo_foreign FOREIGN KEY (id_vuelo) REFERENCES vuelos(id) ON DELETE CASCADE');
    }
};
