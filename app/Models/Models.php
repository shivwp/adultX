<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Models extends Model
{
    use HasFactory;
    protected $table = "models";

    protected $fillable = [
        'user_id',
        'gallery_image',
        'phone',
        'video',
        'Orientation',
        'Ethnicity',
        'Language',
        'Hair',
        'Fetishes',
        'Model_Category',
        'stage_name',
        'url1',
        'url2',
        'url3',
        'cost_msg',
        'cost_pic',
        'cost_videomsg',
        'cost_audiomsg',
        'cost_audiocall',
        'cost_videocall',
        'socail_links',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
