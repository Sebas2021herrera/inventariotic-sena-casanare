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
        Schema::create('areas_seguras', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();                      // YOP-S01-DC
            $table->string('nombre_dependencia');                    // Data Center
            $table->enum('nivel_criticidad', ['Bajo','Medio','Alto']);
            $table->string('responsable_cargo');                     // Cargo del custodio
            $table->string('bloque')->nullable();
            $table->string('piso')->nullable();
            $table->string('numero_oficina')->nullable();
            $table->string('perimetro_seguridad');                   // Muros, Drywall, etc.
            $table->json('controles_acceso');                        // ["Biométrico","Llave física"]
            $table->enum('horario_acceso', ['Jornada laboral','24/7','Restringido']);
            $table->text('descripcion')->nullable();
            $table->boolean('activa')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas_seguras');
    }
};
