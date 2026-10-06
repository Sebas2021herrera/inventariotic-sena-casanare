<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('areas_seguras_verificaciones', function (Blueprint $table) {
            // GIL-F-102 Bloque 1: Personas que inspeccionan (texto libre)
            $table->text('inspectores')->nullable()->after('verificado_por');

            // GIL-F-102 Bloque 3: Tipo de controles medioambientales que aplica
            $table->string('tipo_medioambiental', 50)->nullable()->after('inspectores');

            // GIL-F-102 Bloque 3: Controles medioambientales con 4 estados
            $table->json('items_medioambientales')->nullable()->after('tipo_medioambiental');

            // Contadores adicionales para los 4 estados
            $table->integer('cumple_parcial_count')->default(0)->after('total_cumple');
            $table->integer('no_aplica_count')->default(0)->after('cumple_parcial_count');
        });

        // Agregar 'Cumple Parcialmente' y 'No Aplica' al enum resultado
        \Illuminate\Support\Facades\DB::statement(
            "ALTER TABLE areas_seguras_verificaciones DROP CONSTRAINT IF EXISTS areas_seguras_verificaciones_resultado_check"
        );
        \Illuminate\Support\Facades\DB::statement(
            "ALTER TABLE areas_seguras_verificaciones ADD CONSTRAINT areas_seguras_verificaciones_resultado_check
             CHECK (resultado IN ('Conforme','No Conforme','Conforme con Observaciones','En Proceso'))"
        );
    }

    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement(
            "ALTER TABLE areas_seguras_verificaciones DROP CONSTRAINT IF EXISTS areas_seguras_verificaciones_resultado_check"
        );
        \Illuminate\Support\Facades\DB::statement(
            "ALTER TABLE areas_seguras_verificaciones ADD CONSTRAINT areas_seguras_verificaciones_resultado_check
             CHECK (resultado IN ('Conforme','No Conforme','Conforme con Observaciones'))"
        );

        Schema::table('areas_seguras_verificaciones', function (Blueprint $table) {
            $table->dropColumn([
                'inspectores', 'tipo_medioambiental', 'items_medioambientales',
                'cumple_parcial_count', 'no_aplica_count',
            ]);
        });
    }
};
