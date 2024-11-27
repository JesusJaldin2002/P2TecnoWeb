<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caresheet extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'doctor_id',
        'consult_id',
        'symptoms',
        'diagnosis',
        'notes',
    ];

    /**
     * Relación con el modelo Consult.
     */
    public function consult()
    {
        return $this->belongsTo(Consult::class, 'consult_id', 'id');
    }

    /**
     * Relación con el modelo Doctor.
     */

}
