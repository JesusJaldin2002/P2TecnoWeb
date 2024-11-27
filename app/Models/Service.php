<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'patient_id',
        'employee_id',
        'service_type',
        'price',
    ];

    /**
     * Relación con el modelo Patient.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function vaccine()
    {
        return $this->hasOne(Vaccine::class, 'id', 'id');
    }

    public function consult()
    {
        return $this->hasOne(Consult::class, 'id', 'id');
    }

    public function service()
    {
        return $this->hasOne(Service::class, 'id', 'id');
    }
}
