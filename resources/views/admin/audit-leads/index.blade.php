@extends('admin.layouts.app')
@section('title','Audit Leads')

@section('content')
<div class="card">
    <div class="card-head">
        <div class="card-title">🔍 Free Audit Requests
            @php $unread = \App\Models\AuditLead::where('is_read',false)->count(); @endphp
            @if($unread)<span class="badge badge-blue" style="margin-left:8px">{{ $unread }} new</span>@endif
        </div>
        <a href="{{ route('free-audit') }}" target="_blank" class="btn btn-ghost btn-sm">View Audit Page</a>
    </div>
    @if($leads->count())
    <div class="table-wrap">
        <table>
            <thead><tr><th>Name</th><th>Email</th><th>Website</th><th>Budget</th><th>Services</th><th>Date</th><th>Status</th><th style="text-align:right">Actions</th></tr></thead>
            <tbody>
            @foreach($leads as $lead)
            <tr style="{{ !$lead->is_read ? 'background:#fefcff' : '' }}">
                <td>
                    <div style="font-weight:{{ !$lead->is_read ? '700' : '600' }};color:#1e293b">{{ $lead->name }}</div>
                    @if($lead->business_type)<div style="font-size:12px;color:#94a3b8">{{ $lead->business_type }}</div>@endif
                </td>
                <td><a href="mailto:{{ $lead->email }}" style="color:var(--p);font-size:13px">{{ $lead->email }}</a></td>
                <td>@if($lead->website_url)<a href="{{ $lead->website_url }}" target="_blank" style="font-size:12px;color:var(--s);font-family:monospace">{{ Str::limit($lead->website_url, 30) }}</a>@else<span style="color:#94a3b8">—</span>@endif</td>
                <td>@if($lead->budget_range)<span class="badge badge-green">{{ $lead->budget_range }}</span>@else<span style="color:#94a3b8">—</span>@endif</td>
                <td style="font-size:12px;color:#64748b;max-width:180px">{{ implode(', ', (array)($lead->services_needed ?? [])) ?: '—' }}</td>
                <td style="font-size:12px;color:#94a3b8;white-space:nowrap">{{ $lead->created_at->diffForHumans() }}</td>
                <td><span class="badge {{ $lead->is_read ? 'badge-green' : 'badge-blue' }}">{{ $lead->is_read ? 'Reviewed' : 'New' }}</span></td>
                <td style="text-align:right">
                    <div style="display:flex;gap:6px;justify-content:flex-end">
                        <a href="{{ route('admin.audit-leads.show', $lead) }}" class="btn btn-ghost btn-sm">Open</a>
                        <form method="POST" action="{{ route('admin.audit-leads.destroy', $lead) }}" onsubmit="return confirm('Delete lead?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm">Delete</button></form>
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $leads->links() }}</div>
    @else
    <div class="empty-state">
        <span class="empty-state-icon">🔍</span>
        <p>No audit requests yet. Share the <a href="{{ route('free-audit') }}" style="color:var(--p);font-weight:600" target="_blank">Free Audit page</a> to start collecting leads!</p>
    </div>
    @endif
</div>
@endsection