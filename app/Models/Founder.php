<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Founder extends Model
{
    protected $fillable = [
        'name', 'role', 'photo_path', 'sort_order', 'is_active',
    ];
}
