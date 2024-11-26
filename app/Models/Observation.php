<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'treatment_id',
        'doctor_id',
        'date',
        'weight',
        'height',
        'age',
        'description',
    ];

    /**
     * Relación con el modelo Treatment.
     */
    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }

    /**
     * Relación con el modelo Doctor.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
