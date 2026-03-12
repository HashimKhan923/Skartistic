<?php
// FILE: app/Models/Service.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    protected $fillable = [
        'title','slug','icon','tag_label',
        'hero_headline','hero_subtitle','hero_cta_primary','hero_cta_secondary',
        'offer_tag','offer_title','offer_subtitle','offer_features',
        'techstack_tag','techstack_title','techstack_subtitle','tech_categories',
        'process_tag','process_title','process_subtitle','process_steps',
        'work_tag','work_title','work_subtitle','featured_projects',
        'cta_title','cta_subtitle',
        'short_description','banner_image','is_published','sort_order',
    ];

    protected $casts = [
        'offer_features'    => 'array',
        'tech_categories'   => 'array',
        'process_steps'     => 'array',
        'featured_projects' => 'array',
        'is_published'      => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($m) {
            if (empty($m->slug)) $m->slug = Str::slug($m->title);
        });
    }

    public function getRouteKeyName() { return 'slug'; }
}