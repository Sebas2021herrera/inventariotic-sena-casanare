<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::create('conceptos_tecnicos', function (Blueprint $table) {
        $table->id();
        // Relación con el equipo
        $table->foreignId('dispositivo_id')->constrained('dispositivos')->onDelete('cascade');
        
        // Encabezado Versión 05
        $table->string('tipo_equipo'); // Administrativo / Formación
        $table->string('hostname'); 
        $table->string('num_incidente')->nullable(); // INC
        $table->string('num_requerimiento')->nullable(); // WO
        $table->date('fecha_reporte');

        // Sección 1: Datos Básicos
        $table->string('jefe_inmediato')->nullable();
        $table->text('descripcion_solicitud');

        // Sección 3: Software Base (Guardaremos esto como JSON para mayor flexibilidad)
        $table->json('software_base')->nullable(); 

        // Sección 4: Detalle de la Solicitud
        $table->text('diagnostico_tecnico');
        $table->enum('flujo_solicitud', ['Repuesto', 'Garantía', 'Siniestro', 'Concepto Técnico']);
        $table->enum('concepto_tipo', ['Asignación', 'Reasignación', 'Baja', 'Reparación'])->nullable();
        
        // Firmas y Cierre
        $table->string('tecnico_nombre');
        $table->boolean('requiere_contingencia')->default(false);
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concepto_tecnicos');
    }
};
