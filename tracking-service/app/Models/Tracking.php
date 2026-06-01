<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    use HasFactory;

    protected $table = 'trackings';

    protected $fillable = [
        'order_id',
        'driver_id',
        'status',
        'terakhir_diupdate',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'terakhir_diupdate' => 'datetime',
    ];

    /**
     * Relationship to Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}
