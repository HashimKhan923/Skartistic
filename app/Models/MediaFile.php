<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model
{
    protected $fillable = ['filename','original_name','path','disk','mime_type','size','alt_text','folder'];


}
