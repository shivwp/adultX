<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogComments extends Model
{
    use HasFactory;
        protected $table = "blog_comments";

    protected $fillable = [
        'parent_id',
        'user_id',
        'comment',
        'blog_slug',
        'id'
    ];

     
}
