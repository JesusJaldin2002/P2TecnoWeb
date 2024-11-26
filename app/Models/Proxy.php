<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'phone_number',
        'occupation',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class, 'id', 'id');
    }

    public function patients()
    {
        return $this->hasMany(Patient::class, 'proxy_id', 'id');
    }
}
