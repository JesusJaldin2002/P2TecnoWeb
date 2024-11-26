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
        Schema::create('services', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->unsignedBigInteger('patient_id'); // Relación con patients
            $table->unsignedBigInteger('employee_id'); // Relación con employees (NO NULLABLE)
            $table->char('service_type', 1); // Tipo de servicio ('C', 'T', 'V')
            $table->float('price')->check('price >= 0'); // Precio, con validación

            // Relaciones
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
