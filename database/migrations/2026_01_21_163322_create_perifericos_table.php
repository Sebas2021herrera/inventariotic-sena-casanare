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
    Schema::create('perifericos', function (Blueprint $table) {
        $table->id();
        // Relación: Si se borra el dispositivo, se borran sus periféricos (onDelete cascade)
        $table->foreignId('dispositivo_id')->constrained('dispositivos')->onDelete('cascade');
        
        $table->string('tipo'); // Monitor, Teclado, Mouse, Cargador
        $table->string('placa')->nullable();
        $table->string('serial')->nullable();
        $table->string('marca')->nullable();
        $table->string('modelo')->nullable();
        $table->string('estado')->default('BUENO');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perifericos');
    }
};
