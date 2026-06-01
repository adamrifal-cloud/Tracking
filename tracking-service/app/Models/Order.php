<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'sender_name',
        'sender_phone',
        'sender_address',
        'receiver_name',
        'receiver_phone',
        'receiver_address',
        'package_description',
        'package_weight',
        'price',
        'payment_status',
    ];

    /**
     * Relationship to User (Customer)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tracking record associated with the order.
     */
    public function tracking()
    {
        return $this->hasOne(Tracking::class, 'order_id', 'order_id');
    }
}
