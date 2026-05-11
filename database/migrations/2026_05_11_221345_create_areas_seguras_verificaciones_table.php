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
        Schema::create('areas_seguras_verificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_segura_id')->constrained('areas_seguras')->cascadeOnDelete();
            $table->date('fecha_verificacion');
            $table->string('corte');                   // "Octubre 2026"
            $table->json('items');                     // [{control,categoria,item,cumple,observaciones}]
            $table->integer('total_cumple');
            $table->integer('total_items');
            $table->enum('resultado', ['Conforme','No Conforme','Conforme con Observaciones']);
            $table->text('observaciones_generales')->nullable();
            $table->foreignId('verificado_por')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas_seguras_verificaciones');
    }
};
