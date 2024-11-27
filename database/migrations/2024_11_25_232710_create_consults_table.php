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
        Schema::create('consults', function (Blueprint $table) {
            $table->unsignedBigInteger('id'); // Clave primaria heredada de services
            $table->unsignedBigInteger('doctor_id'); // Relación con doctors
            $table->date('date'); // Fecha de la consulta
            $table->string('reason', 255)->nullable(); 

            $table->primary('id');
            $table->foreign('id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consults');
    }
};
