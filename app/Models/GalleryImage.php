<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $fillable = ['image', 'caption', 'is_published', 'sort_order'];

    protected $casts = ['is_published' => 'boolean'];
}
