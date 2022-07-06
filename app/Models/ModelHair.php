<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class ModelHair extends Model
{
    use HasFactory;
    use Sluggable;
    protected $table = "model_hair";

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
