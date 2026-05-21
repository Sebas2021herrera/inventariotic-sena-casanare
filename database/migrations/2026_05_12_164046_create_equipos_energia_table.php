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
        Schema::create('equipos_energia', function (Blueprint $table) {
            $table->id();

            // ── Identificación y ubicación ────────────────────────────────────
            $table->foreignId('sede_id')->nullable()->constrained('sedes')->nullOnDelete();
            $table->string('cuarto');                                // "Cuarto de comunicaciones", "DC"
            $table->foreignId('ubicacion_id')->nullable()->constrained('ubicaciones')->nullOnDelete();
            $table->enum('tipo', [
                'UPS',
                'Regulador de Voltaje',
                'Planta Eléctrica / Generador',
                'Tablero de Transferencia (ATS)',
                'Estabilizador',
                'PDU',
                'Otro',
            ]);
            $table->string('marca');
            $table->string('modelo');
            $table->string('numero_serie')->nullable();
            $table->string('placa')->nullable();
            $table->string('pertenece')->default('SENA');            // SENA, Arrendado, etc.
            $table->enum('estado', ['Bueno','Regular','Malo','En Mantenimiento','Dado de Baja'])->default('Bueno');
            $table->boolean('marquillado')->default(false);

            // ── Especificaciones eléctricas ────────────────────────────────────
            $table->enum('fase', ['Monofásica','Bifásica','Trifásica'])->nullable();
            $table->decimal('potencia_va', 10, 2)->nullable();       // Potencia nominal VA
            $table->decimal('potencia_w', 10, 2)->nullable();        // Potencia nominal W
            $table->decimal('capacidad_va', 10, 2)->nullable();      // Capacidad de salida VA
            $table->decimal('capacidad_w', 10, 2)->nullable();       // Capacidad de salida W
            $table->decimal('capacidad_a', 10, 2)->nullable();       // Capacidad en Amperios
            $table->decimal('capacidad_conmutacion_a', 10, 2)->nullable(); // Conmutación (A)
            $table->integer('voltaje_entrada')->nullable();           // 110, 220, 380 V
            $table->integer('voltaje_salida')->nullable();
            $table->integer('frecuencia')->default(60);              // 50 / 60 Hz

            // ── Baterías y respaldo ────────────────────────────────────────────
            $table->decimal('capacidad_baterias_ah', 10, 2)->nullable(); // Capacidad Ah
            $table->integer('numero_baterias')->nullable();
            $table->integer('tiempo_respaldo_min')->nullable();      // Respaldo nominal en minutos
            $table->integer('tiempo_respaldo_verificado_min')->nullable(); // Respaldo medido real

            // ── Solo para UPS ──────────────────────────────────────────────────
            $table->enum('tecnologia_ups', ['Online (doble conversión)','Offline (standby)','Line-Interactive'])->nullable();

            // ── Administrativa ─────────────────────────────────────────────────
            $table->date('fecha_instalacion')->nullable();
            $table->date('fecha_ultimo_mantenimiento')->nullable();
            $table->date('proximo_mantenimiento')->nullable();
            $table->date('garantia_hasta')->nullable();
            $table->string('proveedor')->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('activo')->default(true);
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
        Schema::dropIfExists('equipos_energia');
    }
};
