<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $pages = [
            [ "nombre" => "Doctores - Lista", "url" => "/doctors", "visitas" => 0 ],
            [ "nombre" => "Doctores - Crear", "url" => "/doctors/create", "visitas" => 0 ],
            [ "nombre" => "Doctores - Editar", "url" => "/doctors/{doctor}/edit", "visitas" => 0 ],
            [ "nombre" => "Doctores - Mostrar", "url" => "/doctors/{doctor}/show", "visitas" => 0 ],
            [ "nombre" => "Empleados - Lista", "url" => "/employees", "visitas" => 0 ],
            [ "nombre" => "Empleados - Crear", "url" => "/employees/create", "visitas" => 0 ],
            [ "nombre" => "Empleados - Editar", "url" => "/employees/{employee}/edit", "visitas" => 0 ],
            [ "nombre" => "Empleados - Mostrar", "url" => "/employees/{employee}/show", "visitas" => 0 ],
            [ "nombre" => "Salas - Lista", "url" => "/rooms", "visitas" => 0 ],
            [ "nombre" => "Salas - Crear", "url" => "/rooms/create", "visitas" => 0 ],
            [ "nombre" => "Salas - Editar", "url" => "/rooms/{room}/edit", "visitas" => 0 ],
            [ "nombre" => "Apoderados - Lista", "url" => "/proxies", "visitas" => 0 ],
            [ "nombre" => "Apoderados - Crear", "url" => "/proxies/create", "visitas" => 0 ],
            [ "nombre" => "Apoderados - Editar", "url" => "/proxies/{proxy}/edit", "visitas" => 0 ],
            [ "nombre" => "Pacientes - Lista", "url" => "/patients", "visitas" => 0 ],
            [ "nombre" => "Pacientes - Crear", "url" => "/patients/create", "visitas" => 0 ],
            [ "nombre" => "Pacientes - Editar", "url" => "/patients/{patient}/edit", "visitas" => 0 ],
            [ "nombre" => "Productos - Lista", "url" => "/meals", "visitas" => 0 ],
            [ "nombre" => "Productos - Crear", "url" => "/meals/create", "visitas" => 0 ],
            [ "nombre" => "Productos - Editar", "url" => "/meals/{meal}/edit", "visitas" => 0 ],
            [ "nombre" => "Pagos - Lista", "url" => "/payments", "visitas" => 0 ],
            [ "nombre" => "Pagos - Crear", "url" => "/payments/create", "visitas" => 0 ],
            [ "nombre" => "Reportes - Lista", "url" => "/reports", "visitas" => 0 ],
            [ "nombre" => "Reportes - Pagos", "url" => "/reports/payments", "visitas" => 0 ],
            [ "nombre" => "Reportes - Consultas", "url" => "/reports/consults", "visitas" => 0 ],
            [ "nombre" => "Reportes - Tratamientos", "url" => "/reports/treatments", "visitas" => 0 ],
            [ "nombre" => "Estadísticas - Lista", "url" => "/stadistics", "visitas" => 0 ],
            [ "nombre" => "Tratamientos - Lista", "url" => "/treatments", "visitas" => 0 ],
            [ "nombre" => "Tratamientos - Crear", "url" => "/treatments/create", "visitas" => 0 ],
            [ "nombre" => "Tratamientos - Editar", "url" => "/treatments/{treatment}/edit", "visitas" => 0 ],
            [ "nombre" => "Consultas - Lista", "url" => "/consults", "visitas" => 0 ],
            [ "nombre" => "Consultas - Crear", "url" => "/consults/create", "visitas" => 0 ],
            [ "nombre" => "Consultas - Editar", "url" => "/consults/{consult}/edit", "visitas" => 0 ],
            [ "nombre" => "Vacunas - Lista", "url" => "/vaccines", "visitas" => 0 ],
            [ "nombre" => "Vacunas - Crear", "url" => "/vaccines/create", "visitas" => 0 ],
            [ "nombre" => "Vacunas - Editar", "url" => "/vaccines/{vaccine}/edit", "visitas" => 0 ],
            [ "nombre" => "Hojas de Atención - Lista", "url" => "/caresheets", "visitas" => 0 ],
            [ "nombre" => "Hojas de Atención - Crear", "url" => "/caresheets/create", "visitas" => 0 ],
            [ "nombre" => "Hojas de Atención - Editar", "url" => "/caresheets/{caresheet}/edit", "visitas" => 0 ],
            [ "nombre" => "Observaciones - Lista", "url" => "/observations", "visitas" => 0 ],
            [ "nombre" => "Observaciones - Crear", "url" => "/observations/create", "visitas" => 0 ],
            [ "nombre" => "Observaciones - Editar", "url" => "/observations/{observation}/edit", "visitas" => 0 ],
            [ "nombre" => "Observaciones - Mostrar", "url" => "/observations/{treatment}", "visitas" => 0 ],
            [ "nombre" => "Dashboard", "url" => "/dashboard", "visitas" => 0 ],
        ];

        DB::table('pages')->insert($pages);
    }
}
