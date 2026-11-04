<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model {
    protected $fillable = ['title','slug','excerpt','content','featured_image','author','category','is_published','published_at'];
    protected $casts = ['published_at' => 'datetime', 'is_published' => 'boolean'];

    public function getReadTimeAttribute(): string
    {
        return max(1, (int) ceil(str_word_count(strip_tags((string) $this->content)) / 200)).' min read';
    }
}