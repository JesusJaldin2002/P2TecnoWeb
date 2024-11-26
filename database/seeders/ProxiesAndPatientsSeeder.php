<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Person;
use App\Models\Proxy;
use App\Models\Patient;

class ProxiesAndPatientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear 3 proxies como personas
        $proxy1 = Person::create([
            'ci' => 10000001,
            'name' => 'Juan Pérez',
            'address' => 'Calle Falsa 123',
            'gender' => 'M',
            'birth_date' => '1980-01-01',
            'people_type' => 'P', // 'P' para Proxy
        ]);
        $proxy2 = Person::create([
            'ci' => 10000002,
            'name' => 'María López',
            'address' => 'Avenida Siempre Viva 742',
            'gender' => 'F',
            'birth_date' => '1985-05-15',
            'people_type' => 'P', // 'P' para Proxy
        ]);
        $proxy3 = Person::create([
            'ci' => 10000003,
            'name' => 'Carlos Gómez',
            'address' => 'Calle Principal 456',
            'gender' => 'M',
            'birth_date' => '1990-12-20',
            'people_type' => 'P', // 'P' para Proxy
        ]);

        // Crear proxies en la tabla proxies
        Proxy::create([
            'id' => $proxy1->id,
            'phone_number' => 123456789,
            'occupation' => 'Profesor',
        ]);
        Proxy::create([
            'id' => $proxy2->id,
            'phone_number' => 987654321,
            'occupation' => 'Ingeniera',
        ]);
        Proxy::create([
            'id' => $proxy3->id,
            'phone_number' => 112233445,
            'occupation' => 'Abogado',
        ]);

        // Crear 10 pacientes para los primeros dos proxies (5 cada uno)
        $patients = [
            // Pacientes del primer proxy
            ['ci' => 20000001, 'name' => 'Paciente 1', 'address' => 'Zona 1', 'gender' => 'M', 'birth_date' => '2010-03-10', 'blood_type' => 'A', 'rh_factor' => '+', 'proxy_id' => $proxy1->id],
            ['ci' => 20000002, 'name' => 'Paciente 2', 'address' => 'Zona 2', 'gender' => 'F', 'birth_date' => '2011-07-15', 'blood_type' => 'B', 'rh_factor' => '-', 'proxy_id' => $proxy1->id],
            ['ci' => 20000003, 'name' => 'Paciente 3', 'address' => 'Zona 3', 'gender' => 'M', 'birth_date' => '2012-05-12', 'blood_type' => 'O', 'rh_factor' => '+', 'proxy_id' => $proxy1->id],
            ['ci' => 20000004, 'name' => 'Paciente 4', 'address' => 'Zona 4', 'gender' => 'F', 'birth_date' => '2013-02-14', 'blood_type' => 'AB', 'rh_factor' => '-', 'proxy_id' => $proxy1->id],
            ['ci' => 20000005, 'name' => 'Paciente 5', 'address' => 'Zona 5', 'gender' => 'M', 'birth_date' => '2014-08-25', 'blood_type' => 'A', 'rh_factor' => '+', 'proxy_id' => $proxy1->id],

            // Pacientes del segundo proxy
            ['ci' => 20000006, 'name' => 'Paciente 6', 'address' => 'Zona 6', 'gender' => 'M', 'birth_date' => '2010-03-10', 'blood_type' => 'B', 'rh_factor' => '+', 'proxy_id' => $proxy2->id],
            ['ci' => 20000007, 'name' => 'Paciente 7', 'address' => 'Zona 7', 'gender' => 'F', 'birth_date' => '2011-07-15', 'blood_type' => 'O', 'rh_factor' => '-', 'proxy_id' => $proxy2->id],
            ['ci' => 20000008, 'name' => 'Paciente 8', 'address' => 'Zona 8', 'gender' => 'M', 'birth_date' => '2012-05-12', 'blood_type' => 'A', 'rh_factor' => '+', 'proxy_id' => $proxy2->id],
            ['ci' => 20000009, 'name' => 'Paciente 9', 'address' => 'Zona 9', 'gender' => 'F', 'birth_date' => '2013-02-14', 'blood_type' => 'AB', 'rh_factor' => '-', 'proxy_id' => $proxy2->id],
            ['ci' => 20000010, 'name' => 'Paciente 10', 'address' => 'Zona 10', 'gender' => 'M', 'birth_date' => '2014-08-25', 'blood_type' => 'O', 'rh_factor' => '+', 'proxy_id' => $proxy2->id],

            // Pacientes del tercer proxy
            ['ci' => 20000011, 'name' => 'Paciente 11', 'address' => 'Zona 11', 'gender' => 'M', 'birth_date' => '2015-01-01', 'blood_type' => 'A', 'rh_factor' => '-', 'proxy_id' => $proxy3->id],
            ['ci' => 20000012, 'name' => 'Paciente 12', 'address' => 'Zona 12', 'gender' => 'F', 'birth_date' => '2016-02-02', 'blood_type' => 'B', 'rh_factor' => '+', 'proxy_id' => $proxy3->id],
        ];

        foreach ($patients as $patientData) {
            $person = Person::create([
                'ci' => $patientData['ci'],
                'name' => $patientData['name'],
                'address' => $patientData['address'],
                'gender' => $patientData['gender'],
                'birth_date' => $patientData['birth_date'],
                'people_type' => 'A', // 'T' para Paciente
            ]);

            Patient::create([
                'id' => $person->id,
                'blood_type' => $patientData['blood_type'],
                'rh_factor' => $patientData['rh_factor'],
                'proxy_id' => $patientData['proxy_id'],
            ]);
        }
    }
}
