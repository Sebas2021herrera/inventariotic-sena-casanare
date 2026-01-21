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
      Schema::create('responsables', function (Blueprint $table) {
    $table->id();
    $table->string('cedula')->unique();
    $table->string('nombre');
    $table->string('correo_institucional')->nullable();
    $table->string('dependencia');
    $table->string('cargo');
    $table->string('tipo_funcionario'); // Contratista, Planta, etc.
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responsables');
    }
};
