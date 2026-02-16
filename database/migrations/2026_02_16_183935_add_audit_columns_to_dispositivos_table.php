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
    Schema::table('dispositivos', function (Blueprint $table) {
        // Campos para guardar el ID del usuario
        $table->unsignedBigInteger('created_by')->nullable()->after('ubicacion_id');
        $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');

        // Llaves foráneas: Si un usuario se borra, el registro queda (null) para no perder el historial
        $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('dispositivos', function (Blueprint $table) {
        // Es importante el orden inverso para evitar errores de integridad
        $table->dropForeign(['updated_by']);
        $table->dropForeign(['created_by']);
        $table->dropColumn(['created_by', 'updated_by']);
    });
}
};
