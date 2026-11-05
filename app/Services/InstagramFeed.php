<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Posts for the homepage. A connected account shows its latest posts automatically;
 * otherwise any post links pasted in Settings are embedded. Failures never reach the visitor.
 */
class InstagramFeed
{
    private const API = 'https://graph.instagram.com';
    private const LIMIT = 6;

    public function forHomepage(array $settings): array
    {
        $url = trim((string) ($settings['social_instagram'] ?? ''));
        $handle = preg_match('~instagram\.com/([\w.]+)~i', $url, $m) ? $m[1] : null;

        return [
            'url'    => $url,
            'handle' => $handle,
            'posts'  => $this->posts(trim((string) ($settings['instagram_access_token'] ?? ''))),
            'embeds' => $this->embeds((string) ($settings['instagram_posts'] ?? '')),
        ];
    }

    /** Pasted post links become Instagram's own embed pages. Anything else is dropped. */
    public function embeds(string $links): array
    {
        preg_match_all('~instagram\.com/(?:[\w.]+/)?(p|reel|tv)/([\w-]+)~i', $links, $matches, PREG_SET_ORDER);

        $embeds = [];
        foreach ($matches as $match) {
            $embeds[] = 'https://www.instagram.com/'.strtolower($match[1]).'/'.$match[2].'/embed/';
        }

        return array_slice(array_values(array_unique($embeds)), 0, self::LIMIT);
    }

    private function posts(string $token): array
    {
        if ($token === '') {
            return [];
        }

        $key = 'instagram_feed_'.md5($token);
        $cached = Cache::get($key);
        if (is_array($cached)) {
            return $cached;
        }

        $posts = $this->fetch($token);
        // Retry soon after a failure, but never on every page view
        Cache::put($key, $posts, $posts ? now()->addHour() : now()->addMinutes(10));
        $this->refreshToken($token);

        return $posts;
    }

    private function fetch(string $token): array
    {
        try {
            $response = Http::timeout(5)->get(self::API.'/me/media', [
                'fields'       => 'id,caption,media_type,media_url,thumbnail_url,permalink',
                'limit'        => self::LIMIT,
                'access_token' => $token,
            ]);
        } catch (\Throwable $e) {
            report($e);

            return [];
        }

        if (! $response->successful()) {
            return [];
        }

        return collect($response->json('data', []))->map(fn ($post) => [
            'image'     => ($post['media_type'] ?? '') === 'VIDEO' ? ($post['thumbnail_url'] ?? null) : ($post['media_url'] ?? null),
            'permalink' => $post['permalink'] ?? null,
            'caption'   => (string) ($post['caption'] ?? ''),
        ])->filter(fn ($post) => $post['image'] && $post['permalink'])->values()->all();
    }

    /** Long-lived tokens expire after 60 days unless they are refreshed. */
    private function refreshToken(string $token): void
    {
        $refreshedAt = (int) Setting::get('instagram_token_refreshed_at', 0);
        if ($refreshedAt > now()->subDays(30)->timestamp) {
            return;
        }

        try {
            $fresh = Http::timeout(5)->get(self::API.'/refresh_access_token', [
                'grant_type'   => 'ig_refresh_token',
                'access_token' => $token,
            ])->json('access_token');
        } catch (\Throwable $e) {
            report($e);

            return;
        }

        Setting::set('instagram_token_refreshed_at', (string) now()->timestamp);
        if (is_string($fresh) && $fresh !== '') {
            Setting::set('instagram_access_token', $fresh);
        }
    }
}
