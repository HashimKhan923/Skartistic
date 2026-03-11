<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PageView extends Model {
    protected $fillable = ['url','page_title','referrer','ip','user_agent','country','device'];

    public static function record(string $url, string $title = '') {
        $agent = request()->userAgent() ?? '';
        $device = 'desktop';
        if (preg_match('/Mobile|Android|iPhone/i', $agent)) $device = 'mobile';
        elseif (preg_match('/iPad|Tablet/i', $agent)) $device = 'tablet';

        static::create([
            'url'   => $url,
            'page_title' => $title,
            'referrer'   => request()->headers->get('referer', ''),
            'ip' => request()->ip(),
            'user_agent' => substr($agent, 0, 200),
            'device'     => $device,
        ]);
    }
}