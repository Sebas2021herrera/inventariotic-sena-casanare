<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('software_instalado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispositivo_id')->constrained('dispositivos')->cascadeOnDelete();
            $table->foreignId('software_catalogo_id')->constrained('software_catalogo')->cascadeOnDelete();
            $table->date('fecha_instalacion');
            $table->text('version_notas')->nullable();
            $table->foreignId('instalado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['dispositivo_id', 'software_catalogo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('software_instalado');
    }
};
