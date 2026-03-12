<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $fillable = [
        'hero_tag','hero_title','hero_subtitle',
        'mission_title','mission_text_1','mission_text_2',
        'stats_label',
        'stat_clients_num','stat_clients_label',
        'stat_projects_num','stat_projects_label',
        'stat_satisfaction_num','stat_satisfaction_label',
        'stat_experience_num','stat_experience_label',
        'milestones_tag','milestones_title','milestones_subtitle',
        'values_tag','values_title','values_subtitle',
        'faq_tag','faq_title','faq_subtitle',
        'photo_1', 'photo_2', 'photo_3',
    ];
}
