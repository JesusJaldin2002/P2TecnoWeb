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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->integer('capacity')->check('capacity > 0'); // Capacidad debe ser mayor a 0
            $table->integer('available_rooms')->check('available_rooms >= 0'); // Habitaciones disponibles no pueden ser negativas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
