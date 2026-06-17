<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'company_address',
        'document_path',
        'document_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
