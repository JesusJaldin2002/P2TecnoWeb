<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rooms')->insert([
            [
                'name' => 'Habitacion Amarilla',
                'capacity' => 4,
                'available_rooms' => 4,
            ],
            [
                'name' => 'Habitacion Roja',
                'capacity' => 3,
                'available_rooms' => 3,
            ],
            [
                'name' => 'Habitacion Verde',
                'capacity' => 6,
                'available_rooms' => 6,
            ],
        ]);
    }
}
