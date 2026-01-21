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
    Schema::table('dispositivos', function (Blueprint $table) {
        // Campos específicos para SW, AP y Routers
        $table->string('descripcion_tecnica')->nullable(); // Para esa descripción larga de Huawei
        $table->integer('puertos')->nullable();            // No Puertos
        $table->string('mac_address')->nullable();         // MAC (ya la teníamos en especificaciones, pero mejor tenerla aquí para redes)
        $table->string('ap_conectado_a')->nullable();      // AP CONECTADO A SW
        $table->string('puerto_origen')->nullable();       // PUERTO DEL SW
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dispositivos', function (Blueprint $table) {
            //
        });
    }
};
