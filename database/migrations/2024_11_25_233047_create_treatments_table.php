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
        Schema::create('treatments', function (Blueprint $table) {
            $table->unsignedBigInteger('id'); // Clave primaria heredada de services
            $table->string('origin', 255); // Origen del paciente internado
            $table->unsignedBigInteger('room_id')->nullable(); // Relación con rooms
            $table->string('status', 10); // Estado del tratamiento
            $table->primary('id');
            $table->foreign('id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
