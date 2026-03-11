<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Service extends Model {
    protected $fillable = ['title','slug','icon','short_description','content','image','is_published','sort_order'];
}