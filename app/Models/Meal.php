<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name',
        'price',
        'ingredients',
    ];

    /**
     * Relación muchos a muchos con Treatment.
     */
    public function treatments()
    {
        return $this->belongsToMany(Treatment::class, 'meal_treatment')
            ->withPivot('quantity'); // Agregar el campo cantidad
    }
}
