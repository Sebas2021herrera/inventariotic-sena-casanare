<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sgspi_config', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('total_celdas')->default(25);
            $table->unsignedTinyInteger('preguntas')->default(20);
            $table->unsignedTinyInteger('columnas')->default(5);
            $table->timestamps();
        });

        // Fila única de configuración
        DB::table('sgspi_config')->insert([
            'total_celdas' => 25,
            'preguntas'    => 20,
            'columnas'     => 5,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('sgspi_config');
    }
};
