<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    use HasFactory;
        protected $table = "notifications";

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'arab_title',
        'type',
        'body',
        'arab_body',
        'image',
        'status'
    ];
}