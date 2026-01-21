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
        $table->string('propietario')->default('SENA'); // SENA, TELEFONICA, OTRO
        $table->string('funcion')->default('FORMACION'); // ADMINISTRATIVO, FORMACION
        $table->string('en_intune')->default('NO'); // SI, NO
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
