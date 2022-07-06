<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = "transaction";

    protected $fillable = [
        'user_id',
        'user_name',
        'add_money',
        'reason',
        'discription',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
