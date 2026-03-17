<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
        protected $fillable = [
        'name','badge','price','price_suffix','description',
        'features','excluded_features','cta_label','cta_url',
        'is_featured','is_published','sort_order',
    ];
 
    protected $casts = [
        'features'          => 'array',
        'excluded_features' => 'array',
        'is_featured'       => 'boolean',
        'is_published'      => 'boolean',
        'price'             => 'decimal:2',
    ];
}
