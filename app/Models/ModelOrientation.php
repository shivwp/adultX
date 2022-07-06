<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class ModelOrientation extends Model
{
    use HasFactory;
    use Sluggable;
    protected $table = "model_orientation";

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
