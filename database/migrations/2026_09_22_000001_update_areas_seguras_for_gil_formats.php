<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── Paso 1: Migrar datos al nuevo esquema de niveles GIL ─────────────
        // Esto DEBE ir antes del constraint, para que la nueva check constraint no falle.
        DB::statement("ALTER TABLE areas_seguras DROP CONSTRAINT IF EXISTS areas_seguras_nivel_sena_check");
        DB::statement("UPDATE areas_seguras SET nivel_sena = 'Nivel 3' WHERE nivel_sena = 'Nivel 1 - Crítico'");
        DB::statement("UPDATE areas_seguras SET nivel_sena = 'Nivel 1' WHERE nivel_sena = 'Nivel 2 - Sensible'");
        DB::statement("UPDATE areas_seguras SET nivel_sena = 'Nivel 1' WHERE nivel_sena = 'Nivel 3 - Operativo'");
        // Cualquier valor residual que no coincida con los nuevos valores:
        DB::statement("UPDATE areas_seguras SET nivel_sena = 'Nivel 1' WHERE nivel_sena NOT IN ('Nivel 1','Nivel 2','Nivel 3')");
        // Agregar el nuevo constraint ya con los datos migrados
        DB::statement("ALTER TABLE areas_seguras ADD CONSTRAINT areas_seguras_nivel_sena_check CHECK (nivel_sena IN ('Nivel 1','Nivel 2','Nivel 3'))");

        // ── Paso 2: Agregar nuevos campos GIL-F-101 ──────────────────────────
        Schema::table('areas_seguras', function (Blueprint $table) {
            // GIL-F-101: Fecha de Inventario (campo 2)
            $table->date('fecha_inventario')->nullable()->after('codigo');

            // GIL-F-101: Responsable separado en nombre + contacto (campo 5)
            $table->string('responsable_nombre')->nullable()->after('responsable_cargo');
            $table->string('responsable_contacto', 100)->nullable()->after('responsable_nombre');

            // GIL-F-101: Controles de Monitoreo (campo 7)
            $table->json('controles_monitoreo')->nullable()->after('controles_acceso');

            // GIL-F-101: Estado descriptivo (campo 8)
            $table->string('estado_area', 100)->nullable()->after('activa');

            // GIL-F-101: Histórico de cambios append-only (campo 9)
            $table->json('historico_cambios')->nullable()->after('estado_area');

            // GIL-F-101: Clasificación de la Información (footer del formato)
            $table->string('clasificacion_informacion', 50)->nullable()->after('historico_cambios');

            // GIL-F-102 Bloque 1: Zonificación
            $table->string('zonificacion', 40)->nullable()->after('clasificacion_informacion');
        });
    }

    public function down(): void
    {
        Schema::table('areas_seguras', function (Blueprint $table) {
            $table->dropColumn([
                'fecha_inventario', 'responsable_nombre', 'responsable_contacto',
                'controles_monitoreo', 'estado_area', 'historico_cambios',
                'clasificacion_informacion', 'zonificacion',
            ]);
        });

        // Revertir enum a valores originales
        DB::statement("ALTER TABLE areas_seguras DROP CONSTRAINT IF EXISTS areas_seguras_nivel_sena_check");
        DB::statement("UPDATE areas_seguras SET nivel_sena = 'Nivel 3 - Operativo'");
        DB::statement("ALTER TABLE areas_seguras ADD CONSTRAINT areas_seguras_nivel_sena_check CHECK (nivel_sena IN ('Nivel 1 - Crítico','Nivel 2 - Sensible','Nivel 3 - Operativo'))");
    }
};
