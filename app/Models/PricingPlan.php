<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model {
    protected $fillable = ['name','tagline','badge','price','period','is_featured','is_published','sort_order'];

    public function features() {
        return $this->hasMany(PricingFeature::class)->orderBy('sort_order');
    }
}