<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'origin',
        'room_id',
        'status',
    ];

    /**
     * Relación con el modelo Service (generalización).
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'id', 'id');
    }

    /**
     * Relación con el modelo Room.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }


    public function meals()
    {
        return $this->belongsToMany(Meal::class, 'meal_treatment')
                    ->withPivot('quantity');
    }

    public function observations()
    {
        return $this->hasMany(Observation::class);
    }
}
