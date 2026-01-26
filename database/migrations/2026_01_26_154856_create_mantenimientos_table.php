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
    Schema::create('mantenimientos', function (Blueprint $table) {
        $table->id();
        // Relación con el dispositivo
        $table->foreignId('dispositivo_id')->constrained()->onDelete('cascade');
        
        // Datos del mantenimiento
        $table->date('fecha');
        $table->enum('tipo', ['Preventivo', 'Correctivo'])->default('Preventivo');
        $table->text('descripcion_falla')->nullable(); // Solo para correctivos
        $table->text('tareas_realizadas');
        $table->text('piezas_cambiadas')->nullable();
        
        // Quién lo realizó
        $table->string('tecnico_encargado');
        $table->text('observaciones')->nullable();
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mantenimientos');
    }
};
