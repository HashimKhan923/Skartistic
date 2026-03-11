<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Page extends Model {
    protected $fillable = ['title','slug','content','meta_title','meta_description','is_published','show_in_menu','menu_order'];
}