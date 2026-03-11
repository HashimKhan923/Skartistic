<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Career extends Model {
    protected $fillable = ['title','type','location','description','requirements','is_published'];
}