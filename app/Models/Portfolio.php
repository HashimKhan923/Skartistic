<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
        protected $fillable = ['title','slug','category','description','content','thumbnail','images','client','project_url','tags','completed_at','is_published','is_featured','sort_order'];
    protected $casts = ['images' => 'array', 'completed_at' => 'date'];
}
