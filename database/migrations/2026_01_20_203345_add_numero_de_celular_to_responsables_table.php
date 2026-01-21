<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('responsables', function (Blueprint $table) {
            // Agregamos la columna como nullable por si hay registros viejos sin este dato
            $table->string('numero_de_celular')->nullable()->after('cedula');
        });
    }

    public function down(): void
    {
        Schema::table('responsables', function (Blueprint $table) {
            $table->dropColumn('numero_de_celular');
        });
    }
};