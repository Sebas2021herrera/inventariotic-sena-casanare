<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conceptos_tecnicos', function (Blueprint $table) {
            $table->text('causas_daño')->nullable()->after('diagnostico_tecnico');
            $table->text('recomendacion')->nullable()->after('causas_daño');
        });
    }

    public function down(): void
    {
        Schema::table('conceptos_tecnicos', function (Blueprint $table) {
            $table->dropColumn(['causas_daño', 'recomendacion']);
        });
    }
};
