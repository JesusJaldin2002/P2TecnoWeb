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
        Schema::create('page_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('page_id'); // Relación con la tabla `pages`
            $table->unsignedBigInteger('user_id'); // Relación con la tabla `users`
            $table->integer('visitas')->default(1); // Contador de visitas por usuario
            $table->timestamps();
        
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_user');
    }
};
