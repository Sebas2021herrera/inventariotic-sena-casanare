<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phishing_participantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('documento', 30);
            $table->string('area', 120);
            $table->timestamps();
        });

        Schema::create('phishing_resultados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participante_id')->constrained('phishing_participantes')->onDelete('cascade');
            $table->integer('puntaje')->default(0);
            $table->integer('correctas')->default(0);
            $table->integer('total')->default(0);
            $table->integer('bonus')->default(0);
            $table->integer('nivel_alcanzado')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phishing_resultados');
        Schema::dropIfExists('phishing_participantes');
    }
};
