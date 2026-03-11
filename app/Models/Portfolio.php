<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
        protected $fillable = ['title','slug','category','short_description','content','thumbnail','gallery','client_name','project_url','technologies','completed_at','is_published','is_featured','sort_order'];
    protected $casts = ['gallery' => 'array', 'completed_at' => 'date'];
}
