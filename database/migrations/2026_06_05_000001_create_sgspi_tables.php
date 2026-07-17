<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sgspi_participantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('documento', 30);
            $table->string('area', 120);
            $table->timestamps();
        });

        Schema::create('sgspi_preguntas', function (Blueprint $table) {
            $table->id();
            $table->string('tema', 80);
            $table->text('pregunta');
            $table->json('opciones');
            $table->string('respuesta', 1);
            $table->text('explicacion')->nullable();
            $table->timestamps();
        });

        Schema::create('sgspi_resultados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participante_id')->constrained('sgspi_participantes')->onDelete('cascade');
            $table->integer('puntaje')->default(0);
            $table->integer('correctas')->default(0);
            $table->integer('total')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sgspi_resultados');
        Schema::dropIfExists('sgspi_preguntas');
        Schema::dropIfExists('sgspi_participantes');
    }
};
