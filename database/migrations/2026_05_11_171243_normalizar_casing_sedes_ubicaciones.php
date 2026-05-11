<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Normaliza sedes y ubicaciones para eliminar duplicados por casing.
 *
 * Reglas adoptadas:
 *   sedes.nombre        → ucwords(strtolower)  "YOPAL" → "Yopal"
 *   ubicaciones.bloque  → UPPER trim           "cuarto" → "CUARTO"
 *   ubicaciones.ambiente→ UPPER trim           "Biblioteca" → "BIBLIOTECA"
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Normalizar sedes y fusionar duplicados ────────────────────────
        $grupos = DB::table('sedes')->get()
            ->groupBy(fn($s) => ucwords(strtolower(trim($s->nombre))));

        foreach ($grupos as $nombreCanon => $grupo) {
            $canon     = $grupo->sortBy('id')->first();
            $duplicados = $grupo->where('id', '!=', $canon->id)->values();

            DB::table('sedes')->where('id', $canon->id)
                ->update(['nombre' => $nombreCanon]);

            foreach ($duplicados as $dup) {
                DB::table('ubicaciones')
                    ->where('sede_id', $dup->id)
                    ->update(['sede_id' => $canon->id]);
                DB::table('sedes')->where('id', $dup->id)->delete();
            }
        }

        // ── 2. Normalizar bloque y ambiente ─────────────────────────────────
        DB::table('ubicaciones')->get()->each(function ($u) {
            DB::table('ubicaciones')->where('id', $u->id)->update([
                'bloque'   => strtoupper(trim($u->bloque   ?? '')),
                'ambiente' => strtoupper(trim($u->ambiente ?? '')),
            ]);
        });

        // ── 3. Fusionar ubicaciones duplicadas tras normalización ────────────
        $gruposUbic = DB::table('ubicaciones')->orderBy('id')->get()
            ->groupBy(fn($u) => "{$u->sede_id}|{$u->bloque}|{$u->ambiente}");

        foreach ($gruposUbic as $grupo) {
            if ($grupo->count() === 1) continue;

            $canon     = $grupo->sortBy('id')->first();
            $duplicados = $grupo->where('id', '!=', $canon->id)->values();

            foreach ($duplicados as $dup) {
                DB::table('dispositivos')
                    ->where('ubicacion_id', $dup->id)
                    ->update(['ubicacion_id' => $canon->id]);
                DB::table('ubicaciones')->where('id', $dup->id)->delete();
            }
        }
    }

    public function down(): void
    {
        // Normalización irreversible por diseño.
    }
};
