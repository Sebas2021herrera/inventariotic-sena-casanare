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
        Schema::table('dispositivos', function (Blueprint $table) {
            // NULL permitido (equipos sin hostname); PostgreSQL admite múltiples NULLs en UNIQUE
            $table->unique('hostname', 'dispositivos_hostname_unique');
        });
    }

    public function down(): void
    {
        Schema::table('dispositivos', function (Blueprint $table) {
            $table->dropUnique('dispositivos_hostname_unique');
        });
    }
};
