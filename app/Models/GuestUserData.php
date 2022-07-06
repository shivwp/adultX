<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestUserData extends Model
{
    use HasFactory;
    protected $table = "guest_user_data";

    protected $fillable = [
        'device_id',
        'quantity',
        'id',
        'type',
        'product_id',
        'price'
    ];

     
}
