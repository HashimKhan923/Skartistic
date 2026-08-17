<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PageView extends Model {
    protected $fillable = ['url','page_title','referer','ip','user_agent','country'];

    public static function record(string $url, string $title = '') {
        $agent = request()->userAgent() ?? '';

        static::create([
            'url'        => $url,
            'page_title' => $title,
            'referer'    => request()->headers->get('referer', ''),
            'ip'         => request()->ip(),
            'user_agent' => substr($agent, 0, 200),
        ]);
    }
}