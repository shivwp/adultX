<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class ModelCategory extends Model
{
    use HasFactory;
    use Sluggable;
    protected $table = "model_category";

    protected $fillable = [
        'title',
        'slug',
        'status',
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
