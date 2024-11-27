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
        Schema::create('caresheets', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->unsignedBigInteger('consult_id')->unique(); // Relación 1 a 0..1 con consults
            $table->string('symptoms', 255)->nullable(); // Síntomas
            $table->string('diagnosis', 255)->nullable(); // Diagnóstico
            $table->text('notes')->nullable(); 

            // Relaciones
            $table->foreign('consult_id')->references('id')->on('consults')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caresheets');
    }
};
