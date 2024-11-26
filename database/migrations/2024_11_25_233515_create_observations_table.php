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
        Schema::create('observations', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->unsignedBigInteger('treatment_id'); // Relación con treatments
            $table->unsignedBigInteger('doctor_id')->nullable(); // Relación con doctors
            $table->date('date'); // Fecha de la observación
            $table->float('weight')->nullable()->check('weight >= 0'); // Peso no negativo
            $table->float('height')->nullable()->check('height >= 0'); // Altura no negativa
            $table->integer('age')->nullable()->check('age >= 0'); // Edad no negativa
            $table->text('description')->nullable(); // Descripción opcional

            // Relaciones
            $table->foreign('treatment_id')->references('id')->on('treatments')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observations');
    }
};
