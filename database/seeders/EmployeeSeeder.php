<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear el primer empleado
        $user1 = User::create([
            'ci' => 8777721, // Ejemplo de CI
            'name' => 'Empleado 1',
            'email' => 'empleado1@gmail.com',
            'password' => bcrypt('12345678'),
            'phone_number' => 123456789,
            'address' => 'Avenida Secundaria, 456',
            'user_type' => 'E',
            'role_id' => 2
        ]);

        Employee::create([
            'id' => $user1->id,
            'occupation' => 'Recepcionista',
        ]);

        // Crear el segundo empleado
        $user2 = User::create([
            'ci' => 5655512, // Ejemplo de CI
            'name' => 'Empleado 2',
            'email' => 'empleado2@gmail.com',
            'password' => bcrypt('12345678'),
            'phone_number' => 112233445,
            'address' => 'Calle Terciaria, 789',
            'user_type' => 'E', // 'E' para empleado
            'role_id' => 2
        ]);

        Employee::create([
            'id' => $user2->id,
            'occupation' => 'Gerente General',
        ]);
    }
}
