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
      Schema::create('dispositivos', function (Blueprint $table) {
    $table->id();
    $table->string('placa')->unique();
    $table->string('serial')->unique();
    $table->string('marca');
    $table->string('modelo');
    $table->enum('categoria', ['computo', 'impresoras', 'conectividad', 'energia']);
    $table->string('estado_fisico');
    $table->string('estado_logico');
    $table->text('observaciones')->nullable();
    
    // Relaciones
    $table->foreignId('responsable_id')->constrained('responsables');
    $table->foreignId('ubicacion_id')->constrained('ubicaciones');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispositivos');
    }
};
