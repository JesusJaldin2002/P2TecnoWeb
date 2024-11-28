<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // Definir la tabla asociada al modelo
    protected $table = 'roles';

    // Los atributos que se pueden asignar masivamente
    protected $fillable = ['name'];

    // Relación con los usuarios (si un rol tiene muchos usuarios)
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
