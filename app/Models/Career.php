<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
        protected $fillable = [
        'title','department','location','type','experience',
        'summary','description','responsibilities','requirements',
        'benefits','apply_email','apply_url','deadline',
        'is_published','sort_order',
    ];
 
    protected $casts = [
        'responsibilities' => 'array',
        'requirements'     => 'array',
        'benefits'         => 'array',
        'is_published'     => 'boolean',
        'deadline'         => 'date',
    ];
}
