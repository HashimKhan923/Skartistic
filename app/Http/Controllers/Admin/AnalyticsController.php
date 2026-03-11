<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageView;
use App\Models\Contact;
use App\Models\AuditLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $days  = (int) $request->input('days', 30);
        $now   = Carbon::now();
        $start = $now->copy()->subDays($days - 1)->startOfDay();

        // Daily views grouped by date
        $dailyViews = PageView::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', $start)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Total & avg
        $totalViews = PageView::where('created_at', '>=', $start)->count();
        $avgPerDay  = $days > 0 ? round($totalViews / $days) : 0;

        // Top pages
        $topPages = PageView::select('url', DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', $start)
            ->groupBy('url')
            ->orderByDesc('count')
            ->limit(8)
            ->get();

        // Device breakdown from user_agent
        $allAgents = PageView::where('created_at', '>=', $start)
            ->whereNotNull('user_agent')
            ->pluck('user_agent');

        $desktop = 0; $mobile = 0; $tablet = 0;
        foreach ($allAgents as $agent) {
            if (preg_match('/iPad|Tablet/i', $agent))       $tablet++;
            elseif (preg_match('/Mobile|Android|iPhone/i', $agent)) $mobile++;
            else                                             $desktop++;
        }
        $total = max($desktop + $mobile + $tablet, 1);
        $deviceStats = [
            'desktop' => round($desktop / $total * 100),
            'mobile'  => round($mobile  / $total * 100),
            'tablet'  => round($tablet  / $total * 100),
        ];

        // Leads
        $totalLeads  = Contact::count() + AuditLead::count();
        $recentLeads = AuditLead::latest()->take(5)->get();

        return view('admin.analytics.index', compact(
            'days', 'totalViews', 'avgPerDay',
            'dailyViews', 'topPages', 'deviceStats',
            'totalLeads', 'recentLeads'
        ));
    }
}