<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intune_cuentas', function (Blueprint $table) {
            $table->id();
            $table->string('cuenta')->unique();
            $table->string('id_sede', 20);
            $table->string('placa', 50);
            $table->enum('estado', ['activa', 'pendiente'])->default('activa');
            $table->date('fecha_reporte')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->index('placa');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intune_cuentas');
    }
};
