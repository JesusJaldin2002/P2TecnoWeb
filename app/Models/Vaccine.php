<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
    ];

    /**
     * Relación con el modelo Service.
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'id', 'id');
    }
}
