@extends('admin.layouts.app')
@section('title','Analytics')

@section('styles')
<style>
.chart-bar-wrap{display:flex;align-items:flex-end;gap:6px;height:120px;padding:0 4px}
.chart-bar{flex:1;border-radius:6px 6px 0 0;background:linear-gradient(to top,var(--p),var(--s));min-width:20px;transition:opacity .2s;position:relative}
.chart-bar:hover{opacity:.8}
.chart-bar-tip{position:absolute;bottom:calc(100% + 6px);left:50%;transform:translateX(-50%);background:#1e293b;color:#fff;font-size:11px;font-weight:700;padding:4px 8px;border-radius:6px;white-space:nowrap;opacity:0;pointer-events:none;transition:opacity .2s}
.chart-bar:hover .chart-bar-tip{opacity:1}
.chart-labels{display:flex;gap:6px;padding:6px 4px 0;font-size:10px;color:#94a3b8;font-weight:600}
.chart-labels span{flex:1;text-align:center}
.metric-big{font-family:'Syne',sans-serif;font-size:2.8rem;font-weight:800;letter-spacing:-1.5px;line-height:1}
.device-bar{height:8px;border-radius:999px;overflow:hidden;background:#f1f5f9;margin-top:8px;display:flex}
.device-seg{height:100%;transition:width .8s}
.top-page{display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid #f8fafc}
.top-page:last-child{border-bottom:none}
.top-page-bar{height:4px;border-radius:999px;margin-top:6px;background:linear-gradient(90deg,var(--p),var(--s))}
</style>
@endsection

@section('content')

{{-- Date filter --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px">
    <div>
        <h2 style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:#1e293b">Site Analytics</h2>
        <p style="font-size:13px;color:#64748b;margin-top:2px">Track your website traffic and visitor behaviour</p>
    </div>
    <div style="display:flex;gap:8px">
        @foreach([7=>'7 days',30=>'30 days',90=>'90 days'] as $d=>$l)
        <a href="?days={{ $d }}" style="padding:8px 16px;border-radius:10px;font-size:13px;font-weight:600;border:1.5px solid {{ ($days??30)==$d?'var(--p)':'#e2e8f0' }};background:{{ ($days??30)==$d?'rgba(124,58,237,.08)':'#fff' }};color:{{ ($days??30)==$d?'var(--p)':'#64748b' }};transition:all .2s">{{ $l }}</a>
        @endforeach
    </div>
</div>

{{-- Top stats --}}
<div class="stats-row" style="margin-bottom:24px">
    <div class="stat-box">
        <div class="stat-box-icon">👁️</div>
        <div class="metric-big" style="color:var(--p)">{{ number_format($totalViews) }}</div>
        <div class="stat-box-lbl">Total Page Views</div>
        <div style="font-size:12px;color:#10b981;font-weight:600;margin-top:8px">Last {{ $days??30 }} days</div>
    </div>
    <div class="stat-box">
        <div class="stat-box-icon">📅</div>
        <div class="metric-big" style="color:var(--s)">{{ number_format($avgPerDay) }}</div>
        <div class="stat-box-lbl">Avg. Views / Day</div>
    </div>
    <div class="stat-box">
        <div class="stat-box-icon">📱</div>
        <div class="metric-big" style="color:var(--warn)">{{ $deviceStats['mobile']??0 }}%</div>
        <div class="stat-box-lbl">Mobile Traffic</div>
    </div>
    <div class="stat-box">
        <div class="stat-box-icon">📬</div>
        <div class="metric-big" style="color:var(--success)">{{ $totalLeads }}</div>
        <div class="stat-box-lbl">Total Leads</div>
        <div style="font-size:12px;color:#64748b;margin-top:8px">Contacts + Audits</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr 300px;gap:22px;align-items:start">

    {{-- Daily chart --}}
    <div class="card" style="grid-column:1/3">
        <div class="card-head"><div class="card-title">📈 Daily Page Views</div></div>
        <div class="card-body">
            @if($dailyViews->count())
            @php $max = $dailyViews->max('count') ?: 1; @endphp
            <div class="chart-bar-wrap">
                @foreach($dailyViews as $day)
                <div class="chart-bar" style="height:{{ max(4, ($day->count/$max)*100) }}%">
                    <div class="chart-bar-tip">{{ $day->count }} views</div>
                </div>
                @endforeach
            </div>
            <div class="chart-labels">
                @foreach($dailyViews as $day)
                <span>{{ \Carbon\Carbon::parse($day->date)->format('d') }}</span>
                @endforeach
            </div>
            @else
            <div style="text-align:center;padding:40px;color:#94a3b8">
                <div style="font-size:2.5rem;margin-bottom:10px">📊</div>
                <p>No analytics data yet. Views will appear here as visitors browse your site.</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Device breakdown --}}
    <div class="card">
        <div class="card-head"><div class="card-title">📱 Device Types</div></div>
        <div class="card-body">
            @php
                $desktop = $deviceStats['desktop']??0;
                $mobile  = $deviceStats['mobile']??0;
                $tablet  = $deviceStats['tablet']??0;
            @endphp
            <div class="device-bar">
                <div class="device-seg" style="width:{{ $desktop }}%;background:var(--p)"></div>
                <div class="device-seg" style="width:{{ $mobile }}%;background:var(--s)"></div>
                <div class="device-seg" style="width:{{ $tablet }}%;background:var(--warn)"></div>
            </div>
            <div style="display:flex;flex-direction:column;gap:10px;margin-top:18px">
                @foreach([['Desktop',$desktop,'var(--p)','🖥️'],['Mobile',$mobile,'var(--s)','📱'],['Tablet',$tablet,'var(--warn)','📟']] as $d)
                <div style="display:flex;align-items:center;justify-content:space-between">
                    <span style="font-size:14px;color:#374151;display:flex;align-items:center;gap:8px">
                        <span style="width:10px;height:10px;border-radius:50%;background:{{ $d[2] }};flex-shrink:0"></span>
                        {{ $d[3] }} {{ $d[0] }}
                    </span>
                    <span style="font-family:'Syne',sans-serif;font-weight:700;color:#1e293b">{{ $d[1] }}%</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Top pages --}}
    <div class="card">
        <div class="card-head"><div class="card-title">🔥 Top Pages</div></div>
        <div class="card-body">
            @if($topPages->count())
            @php $maxPage = $topPages->max('count') ?: 1; @endphp
            @foreach($topPages->take(8) as $page)
            <div class="top-page">
                <div style="flex:1;min-width:0">
                    <div style="font-size:13px;font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $page->page_url }}</div>
                    <div class="top-page-bar" style="width:{{ ($page->count/$maxPage)*100 }}%"></div>
                </div>
                <span style="font-family:'Syne',sans-serif;font-weight:700;color:var(--p);font-size:14px;margin-left:12px;flex-shrink:0">{{ $page->count }}</span>
            </div>
            @endforeach
            @else
            <div style="text-align:center;padding:30px;color:#94a3b8;font-size:14px">No page view data yet.</div>
            @endif
        </div>
    </div>

    {{-- Recent leads --}}
    <div class="card" style="grid-column:1/-1">
        <div class="card-head">
            <div class="card-title">🔥 Recent Audit Leads</div>
            <a href="{{ route('admin.audit-leads.index') }}" class="btn btn-ghost btn-sm">View All</a>
        </div>
        @if($recentLeads->count())
        <div class="table-wrap">
            <table>
                <thead><tr><th>Name</th><th>Email</th><th>Business Type</th><th>Budget</th><th>Services</th><th>Date</th><th>Status</th></tr></thead>
                <tbody>
                @foreach($recentLeads as $lead)
                <tr>
                    <td style="font-weight:600;color:#1e293b">{{ $lead->name }}</td>
                    <td><a href="mailto:{{ $lead->email }}" style="color:var(--p);font-size:13px">{{ $lead->email }}</a></td>
                    <td style="color:#64748b;font-size:13px">{{ $lead->business_type??'—' }}</td>
                    <td><span class="badge badge-blue">{{ $lead->budget_range??'—' }}</span></td>
                    <td style="font-size:12px;color:#64748b;max-width:200px">{{ implode(', ', $lead->services_needed??[]) ?: '—' }}</td>
                    <td style="font-size:12px;color:#94a3b8;white-space:nowrap">{{ $lead->created_at->diffForHumans() }}</td>
                    <td>
                        <span class="badge {{ $lead->is_read?'badge-green':'badge-yellow' }}">{{ $lead->is_read?'Reviewed':'New' }}</span>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state"><span class="empty-state-icon">🔍</span><p>No audit leads yet. Share the <a href="{{ route('free-audit') }}" style="color:var(--p);font-weight:600">Free Audit page</a> to start collecting leads.</p></div>
        @endif
    </div>
</div>
@endsection