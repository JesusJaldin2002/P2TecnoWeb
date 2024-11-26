<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consult extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'date',
        'reason',
    ];

    /**
     * Relación con el modelo Service (generalización).
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'id', 'id');
    }

    /**
     * Relación con el modelo Caresheet (1 a 0..1).
     */
    public function caresheet()
    {
        return $this->hasOne(Caresheet::class, 'consult_id', 'id');
    }
}
