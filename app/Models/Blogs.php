<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Blogs extends Model
{
    use HasFactory;
    use Sluggable;
        protected $table = "blogs";

    protected $fillable = [
        'cat_slug',
        'author_id',
        'title',
        'slug',
        'short_description',
        'long_description',
        'image',
        'id',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'tag_slug',
        'blog_images',
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
