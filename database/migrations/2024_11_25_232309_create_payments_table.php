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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id')->unique();
            $table->date('date'); // Fecha del pago
            $table->float('total')->check('total >= 0');
            $table->string('tigo_transaction_id')->nullable(); // ID de transacción Tigo
            $table->string('payment_time')->nullable();
            $table->string('payment_type')->nullable();
            // Relación con services
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
