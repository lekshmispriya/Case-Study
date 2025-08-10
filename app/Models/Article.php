<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'source',
        'title',
        'description',
        'url',
        'url_to_image',
        'published_at',
        'author',
        'category'
    ];
}
