<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('intune_cuentas', function (Blueprint $table) {
            $table->enum('tipo', ['dispositivo', 'usuario'])->default('dispositivo')->after('estado');
        });
    }

    public function down(): void
    {
        Schema::table('intune_cuentas', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }
};
