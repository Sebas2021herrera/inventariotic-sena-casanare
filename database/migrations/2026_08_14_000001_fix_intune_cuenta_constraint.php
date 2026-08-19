<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Para cuentas tipo='dispositivo': la cuenta es inherentemente única (formato {sede}_{placa}@senaedu)
        // Para cuentas tipo='usuario': la misma persona puede tener múltiples dispositivos, mismo email
        // Solución: unicidad en (placa, tipo) en lugar de solo cuenta

        // 1. Eliminar restricción única actual en cuenta
        Schema::table('intune_cuentas', function (Blueprint $table) {
            $table->dropUnique(['cuenta']);
        });

        // 2. Agregar unicidad en (placa, tipo) — un dispositivo solo puede tener una cuenta por tipo
        Schema::table('intune_cuentas', function (Blueprint $table) {
            $table->unique(['placa', 'tipo'], 'intune_cuentas_placa_tipo_unique');
        });

        // 3. Índice parcial único en cuenta para tipo='dispositivo' (garantiza unicidad de cuentas de dispositivo)
        DB::statement("CREATE UNIQUE INDEX intune_cuenta_dispositivo_unique ON intune_cuentas(cuenta) WHERE tipo = 'dispositivo'");
    }

    public function down(): void
    {
        DB::statement("DROP INDEX IF EXISTS intune_cuenta_dispositivo_unique");

        Schema::table('intune_cuentas', function (Blueprint $table) {
            $table->dropUnique('intune_cuentas_placa_tipo_unique');
        });

        Schema::table('intune_cuentas', function (Blueprint $table) {
            $table->unique('cuenta');
        });
    }
};
