<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'ci',
        'name',
        'address',
        'gender',
        'birth_date',
        'people_type',
    ];

    public function proxy()
    {
        return $this->hasOne(Proxy::class, 'id', 'id');
    }

    public function patient()
    {
        return $this->hasOne(Patient::class, 'id', 'id');
    }
}
