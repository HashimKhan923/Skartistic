<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model {
    protected $fillable = ['title','slug','excerpt','content','featured_image','author','category','is_published','published_at'];
    protected $casts = ['published_at' => 'datetime'];
}