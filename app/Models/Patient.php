<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'blood_type',
        'rh_factor',
        'proxy_id',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class, 'id', 'id');
    }

    public function proxy()
    {
        return $this->belongsTo(Proxy::class, 'proxy_id', 'id');
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
