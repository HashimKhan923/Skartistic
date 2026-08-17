<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientLogo extends Model
{
        protected $fillable = ['name','logo','website_url','is_published','sort_order'];

}
