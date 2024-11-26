<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = [
            ['ci' => 12345678, 'name' => 'Dr. Jesús', 'email' => 'jesus@gmail.com', 'number_ss' => '12345-67890'],
            ['ci' => 23456789, 'name' => 'Dr. Ana', 'email' => 'ana@gmail.com', 'number_ss' => '23456-78901'],
            ['ci' => 34567890, 'name' => 'Dr. Carlos', 'email' => 'carlos@gmail.com', 'number_ss' => '34567-89012'],
            ['ci' => 45678901, 'name' => 'Dr. María', 'email' => 'maria@gmail.com', 'number_ss' => '45678-90123'],
            ['ci' => 56789012, 'name' => 'Dr. José', 'email' => 'jose@gmail.com', 'number_ss' => '56789-01234'],
            ['ci' => 67890123, 'name' => 'Dr. Sofía', 'email' => 'sofia@gmail.com', 'number_ss' => '67890-12345'],
            ['ci' => 78901234, 'name' => 'Dr. Pedro', 'email' => 'pedro@gmail.com', 'number_ss' => '78901-23456'],
            ['ci' => 89012345, 'name' => 'Dr. Isabel', 'email' => 'isabel@gmail.com', 'number_ss' => '89012-34567'],
            ['ci' => 90123456, 'name' => 'Dr. Luis', 'email' => 'luis@gmail.com', 'number_ss' => '90123-45678'],
            ['ci' => 11234567, 'name' => 'Dr. Carmen', 'email' => 'carmen@gmail.com', 'number_ss' => '11234-56789'],
            ['ci' => 22345678, 'name' => 'Dr. Miguel', 'email' => 'miguel@gmail.com', 'number_ss' => '22345-67890'],
            ['ci' => 33456789, 'name' => 'Dr. Laura', 'email' => 'laura@gmail.com', 'number_ss' => '33456-78901'],
        ];

        foreach ($doctors as $data) {
            // Crear el usuario
            $user = User::create([
                'ci' => $data['ci'],
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt('12345678'), // Contraseña por defecto
                'phone_number' => rand(100000000, 999999999), // Número de teléfono aleatorio
                'address' => 'Calle ' . rand(1, 100) . ', Casa ' . rand(1, 100),
                'user_type' => 'D', // Tipo de usuario 'D' para doctor
            ]);

            // Asociar al modelo Doctor
            Doctor::create([
                'id' => $user->id,
                'number_ss' => $data['number_ss'],
            ]);
        }
    }
}
