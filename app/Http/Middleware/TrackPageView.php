<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PageView;

class TrackPageView
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only track GET requests, not admin, not AJAX
        if ($request->isMethod('GET')
            && !$request->is('admin/*')
            && !$request->ajax()
            && !$request->expectsJson()
            && $response->status() === 200
        ) {
            try {
                PageView::create([
                    'url'        => $request->path(),
                    'page_title' => null,
                    'ip'         => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'referer'    => $request->headers->get('referer'),
                ]);
            } catch (\Exception $e) {
                // Silently fail — tracking should never break the site
            }
        }

        return $response;
    }
}