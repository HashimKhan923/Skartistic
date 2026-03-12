<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    protected $fillable = [
        'platform', 'achievement', 'year', 'logo_path', 'sort_order', 'is_active',
    ];
}
