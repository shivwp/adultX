<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class BlogsCategory extends Model
{
    use HasFactory;
     use Sluggable;
        protected $table = "blogs_category";

    protected $fillable = [
        'title',
        'arab_title',
        'slug',
        'id'
    ];


     public function sluggable(): array

    {

        return [

            'slug' => [

                'source' => 'title'

            ]

        ];

    }

}
