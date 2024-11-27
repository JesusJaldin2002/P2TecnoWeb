<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'service_id',
        'date',
        'tigo_transaction_id',
        'payment_time',
        'total',
        'payment_type',
    ];

    /**
     * Relación con el modelo Service.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
