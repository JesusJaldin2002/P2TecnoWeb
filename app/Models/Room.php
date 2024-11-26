<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'capacity',
        'available_rooms',
    ];


    public function treatments()
    {
        return $this->hasMany(Treatment::class);
    }
}
