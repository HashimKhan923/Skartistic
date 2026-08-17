<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Founder extends Model
{
    protected $fillable = [
        'name', 'bio', 'company', 'photo', 'website', 'linkedin', 'twitter', 'sort_order', 'is_active',
    ];
}
