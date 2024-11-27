<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'number_ss',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    public function observations()
    {
        return $this->hasMany(Observation::class);
    }

    public function consults()
    {
        return $this->hasMany(Consult::class);
    }
}
