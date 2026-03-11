<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PricingFeature extends Model {
    protected $fillable = ['pricing_plan_id','feature','included','sort_order'];
}