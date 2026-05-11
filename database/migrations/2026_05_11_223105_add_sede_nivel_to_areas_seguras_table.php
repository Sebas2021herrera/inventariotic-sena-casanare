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
        Schema::table('areas_seguras', function (Blueprint $table) {
            // Sede a la que pertenece el área segura
            $table->foreignId('sede_id')->nullable()->after('id')
                  ->constrained('sedes')->nullOnDelete();

            // Clasificación SENA (Niveles 1-2-3 según lineamientos del Centro)
            $table->enum('nivel_sena', [
                'Nivel 1 - Crítico',   // Data Center, Racks, Bóveda
                'Nivel 2 - Sensible',  // Oficinas Admin, Coordinación
                'Nivel 3 - Operativo', // Ambientes formación, Bodegas
            ])->after('nivel_criticidad')->default('Nivel 3 - Operativo');

            // Tipo de área para ejemplos orientativos
            $table->string('tipo_area')->nullable()->after('nivel_sena');
        });
    }

    public function down(): void
    {
        Schema::table('areas_seguras', function (Blueprint $table) {
            $table->dropConstrainedForeignId('sede_id');
            $table->dropColumn(['nivel_sena', 'tipo_area']);
        });
    }
};
