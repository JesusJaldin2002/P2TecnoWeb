<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'url', 'visitas'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('visitas');
    }
}