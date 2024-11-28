<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')
                  ->constrained('roles') // Relaciona con la tabla 'roles'
                  ->onDelete('cascade'); // Elimina los usuarios si el rol es eliminado
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Elimina la columna 'role_id' y la restricción de llave foránea
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};
