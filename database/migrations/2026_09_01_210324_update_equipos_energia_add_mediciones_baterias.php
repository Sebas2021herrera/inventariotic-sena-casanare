<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipos_energia', function (Blueprint $table) {

            // ── Quitar campos de la versión anterior ────────────────────────────
            $table->dropColumn([
                'capacidad_va',
                'capacidad_w',
                'capacidad_a',
                'capacidad_conmutacion_a',
                'numero_baterias',
                'tiempo_respaldo_min',
                'tiempo_respaldo_verificado_min',
                'tecnologia_ups',        // enum — se reemplaza por string libre
            ]);

            // ── Especificaciones nominales ───────────────────────────────────────
            $table->string('tecnologia', 150)->nullable()->after('frecuencia');        // "Online (doble conversión)", etc.
            $table->decimal('factor_de_potencia', 4, 2)->nullable()->after('tecnologia'); // 0.90

            // ── Mediciones actuales ──────────────────────────────────────────────
            $table->decimal('carga_actual_pct', 5, 2)->nullable()->after('factor_de_potencia');
            $table->decimal('potencia_activa_actual_kw', 10, 3)->nullable()->after('carga_actual_pct');
            $table->decimal('potencia_aparente_actual_kva', 10, 3)->nullable()->after('potencia_activa_actual_kw');
            $table->decimal('bateria_actual_pct', 5, 2)->nullable()->after('potencia_aparente_actual_kva');
            $table->integer('autonomia_estimada_min')->nullable()->after('bateria_actual_pct');
            $table->string('estado_operativo', 150)->nullable()->after('autonomia_estimada_min'); // "SAI correcto", etc.

            // ── Baterías y respaldo (nueva estructura) ───────────────────────────
            $table->string('tipo_bateria', 150)->nullable()->after('estado_operativo');  // "VRLA sellada"
            // capacidad_baterias_ah ya existe (Ah por batería)
            $table->integer('baterias_internas')->nullable()->after('tipo_bateria');
            $table->integer('baterias_banco_externo')->nullable()->after('baterias_internas');
            $table->string('modelo_banco', 150)->nullable()->after('baterias_banco_externo');
            $table->string('serial_banco', 150)->nullable()->after('modelo_banco');
        });
    }

    public function down(): void
    {
        Schema::table('equipos_energia', function (Blueprint $table) {
            $table->dropColumn([
                'tecnologia','factor_de_potencia',
                'carga_actual_pct','potencia_activa_actual_kw','potencia_aparente_actual_kva',
                'bateria_actual_pct','autonomia_estimada_min','estado_operativo',
                'tipo_bateria','baterias_internas','baterias_banco_externo','modelo_banco','serial_banco',
            ]);

            $table->decimal('capacidad_va', 10, 2)->nullable();
            $table->decimal('capacidad_w', 10, 2)->nullable();
            $table->decimal('capacidad_a', 10, 2)->nullable();
            $table->decimal('capacidad_conmutacion_a', 10, 2)->nullable();
            $table->integer('numero_baterias')->nullable();
            $table->integer('tiempo_respaldo_min')->nullable();
            $table->integer('tiempo_respaldo_verificado_min')->nullable();
            $table->enum('tecnologia_ups', ['Online (doble conversión)','Offline (standby)','Line-Interactive'])->nullable();
        });
    }
};
