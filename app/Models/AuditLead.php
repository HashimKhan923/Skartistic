<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AuditLead extends Model {
    protected $fillable = [
        'name','email','phone','website_url','business_type',
        'budget_range','goals','services_needed','is_read'
    ];
    protected $casts = ['services_needed' => 'array', 'is_read' => 'boolean'];
}