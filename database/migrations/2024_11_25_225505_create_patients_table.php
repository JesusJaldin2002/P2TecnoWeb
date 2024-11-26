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
        Schema::create('patients', function (Blueprint $table) {
            $table->unsignedBigInteger('id'); // Clave primaria y foránea
            $table->string('blood_type');
            $table->char('rh_factor', 1);
            $table->unsignedBigInteger('proxy_id'); // Relación con proxies

            $table->primary('id'); // Clave primaria
            $table->foreign('id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('proxy_id')->references('id')->on('proxies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
