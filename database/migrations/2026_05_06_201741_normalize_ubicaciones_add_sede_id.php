<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // Mapa canónico: UPPER(TRIM(sede)) → nombre oficial
    private array $canonico = [
        'AGUAZUL'        => 'Aguazul',
        'MONTERREY'      => 'Monterrey',
        'PAZ DE ARIPORO' => 'Paz de Ariporo',
        'YOPAL'          => 'Yopal',
    ];

    public function up(): void
    {
        // ── 1. Agregar sede_id nullable ───────────────────────────────────
        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->foreignId('sede_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('sedes')
                  ->onDelete('cascade');
        });

        // ── 2. Insertar sedes canónicas y mapear ubicaciones ──────────────
        $now = now();

        foreach ($this->canonico as $upper => $nombre) {
            $sedeId = DB::table('sedes')->insertGetId([
                'nombre'     => $nombre,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('ubicaciones')
                ->whereRaw("UPPER(TRIM(sede)) = ?", [$upper])
                ->update(['sede_id' => $sedeId]);
        }

        // ── 3. Cualquier ubicacion que no matchee al mapa → sede "Otra" ──
        $orphans = DB::table('ubicaciones')->whereNull('sede_id')->count();
        if ($orphans > 0) {
            $otraId = DB::table('sedes')->insertGetId([
                'nombre'     => 'Otra',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            DB::table('ubicaciones')
                ->whereNull('sede_id')
                ->update(['sede_id' => $otraId]);
        }

        // ── 4. Hacer sede_id NOT NULL ─────────────────────────────────────
        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->foreignId('sede_id')->nullable(false)->change();
        });

        // ── 5. Eliminar columna sede (string) ─────────────────────────────
        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->dropColumn('sede');
        });
    }

    public function down(): void
    {
        // Restaurar columna sede
        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->string('sede')->nullable()->after('id');
        });

        // Repoblar sede desde la relación
        DB::table('ubicaciones')
            ->join('sedes', 'ubicaciones.sede_id', '=', 'sedes.id')
            ->update(['ubicaciones.sede' => DB::raw('sedes.nombre')]);

        // Quitar FK y columna sede_id
        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->dropForeign(['sede_id']);
            $table->dropColumn('sede_id');
        });

        // Borrar tabla sedes
        Schema::dropIfExists('sedes');
    }
};
