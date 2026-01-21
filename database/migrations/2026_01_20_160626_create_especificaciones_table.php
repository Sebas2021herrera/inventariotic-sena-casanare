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
       Schema::create('especificaciones', function (Blueprint $table) {
    $table->id();
    $table->foreignId('dispositivo_id')->constrained('dispositivos')->onDelete('cascade');
    $table->string('procesador')->nullable();
    $table->string('ram')->nullable();
    $table->string('tipo_disco')->nullable();
    $table->string('capacidad_disco')->nullable();
    $table->string('so')->nullable(); // Sistema Operativo
    $table->string('mac_address')->nullable();
    
    // Campos para periféricos (específicos de tu Excel)
    $table->string('placa_monitor')->nullable();
    $table->string('serial_cargador')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('especificaciones');
    }
};
