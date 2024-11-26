<?php

namespace Database\Seeders;

use App\Models\Meal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Meal::insert([
            [
                'name' => 'Papilla Energética',
                'price' => 50.00,
                'ingredients' => 'Plátano, Avena, Leche, Miel',
            ],
            [
                'name' => 'Papilla de Verduras',
                'price' => 25.00,
                'ingredients' => 'Calabaza, Zanahoria, Apio, Papa',
            ],
            [
                'name' => 'Papilla de Espinacas',
                'price' => 30.00,
                'ingredients' => 'Espinacas, Papa, Crema, Mantequilla',
            ],
            [
                'name' => 'Papilla de Frutas',
                'price' => 15.00,
                'ingredients' => 'Manzana, Pera, Naranja, Miel',
            ],
            [
                'name' => 'Papilla de Lentejas',
                'price' => 40.50,
                'ingredients' => 'Lentejas, Tomate, Zanahoria, Cebolla, Pimientos',
            ],
        ]);
    }
}
