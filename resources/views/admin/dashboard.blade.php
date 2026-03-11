@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
{{-- Stats --}}
<div class="stats-row">
    <div class="stat-box" style="--box-color:rgba(124,58,237,.08)">
        <div class="stat-box-icon">📬</div>
        <div class="stat-box-num" style="color:{{ $unread_contacts > 0 ? 'var(--danger)' : 'var(--p)' }}">{{ $unread_contacts }}</div>
        <div class="stat-box-lbl">Unread Messages</div>
        <div class="stat-box-change change-{{ $unread_contacts > 0 ? 'down' : 'up' }}">
            {{ $unread_contacts > 0 ? '⚡ Needs attention' : '✓ All caught up' }}
        </div>
    </div>
    <div class="stat-box" style="--box-color:rgba(6,182,212,.08)">
        <div class="stat-box-icon">⚡</div>
        <div class="stat-box-num" style="color:var(--s)">{{ $services_count }}</div>
        <div class="stat-box-lbl">Services</div>
        <a href="{{ route('admin.services.index') }}" style="font-size:12px;color:var(--s);font-weight:600;display:inline-block;margin-top:8px">Manage →</a>
    </div>
    <div class="stat-box" style="--box-color:rgba(16,185,129,.08)">
        <div class="stat-box-icon">✏️</div>
        <div class="stat-box-num" style="color:var(--success)">{{ $posts_count }}</div>
        <div class="stat-box-lbl">Blog Posts</div>
        <a href="{{ route('admin.blog.index') }}" style="font-size:12px;color:var(--success);font-weight:600;display:inline-block;margin-top:8px">Manage →</a>
    </div>
    <div class="stat-box" style="--box-color:rgba(245,158,11,.08)">
        <div class="stat-box-icon">👥</div>
        <div class="stat-box-num" style="color:var(--warn)">{{ $team_count }}</div>
        <div class="stat-box-lbl">Team Members</div>
        <a href="{{ route('admin.team.index') }}" style="font-size:12px;color:var(--warn);font-weight:600;display:inline-block;margin-top:8px">Manage →</a>
    </div>
    <div class="stat-box" style="--box-color:rgba(239,68,68,.06)">
        <div class="stat-box-icon">💬</div>
        <div class="stat-box-num">{{ $contacts_count }}</div>
        <div class="stat-box-lbl">Total Messages</div>
        <a href="{{ route('admin.contacts.index') }}" style="font-size:12px;color:var(--p);font-weight:600;display:inline-block;margin-top:8px">View all →</a>
    </div>
</div>

{{-- Grid --}}
<div style="display:grid;grid-template-columns:1fr 300px;gap:22px;align-items:start">
    {{-- Recent messages --}}
    <div class="card">
        <div class="card-head">
            <div class="card-title">📬 Recent Messages</div>
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-ghost btn-sm">View All</a>
        </div>
        @if($recent_contacts->count())
        <div class="table-wrap">
            <table>
                <thead><tr><th>From</th><th>Subject</th><th>Date</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @foreach($recent_contacts as $c)
                <tr>
                    <td>
                        <div style="font-weight:600;color:#1e293b">{{ $c->name }}</div>
                        <div style="font-size:12px;color:#94a3b8">{{ $c->email }}</div>
                    </td>
                    <td>{{ $c->subject ?? 'General Inquiry' }}</td>
                    <td style="white-space:nowrap;color:#64748b;font-size:13px">{{ $c->created_at->diffForHumans() }}</td>
                    <td><span class="badge {{ $c->is_read ? 'badge-green' : 'badge-blue' }}">{{ $c->is_read ? 'Read' : 'New' }}</span></td>
                    <td><a href="{{ route('admin.contacts.show', $c) }}" class="btn btn-ghost btn-sm">View</a></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <span class="empty-state-icon">📭</span>
            <p>No messages yet</p>
        </div>
        @endif
    </div>

    {{-- Quick actions --}}
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-head"><div class="card-title">⚡ Quick Actions</div></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:8px">
                @foreach([
                    ['icon'=>'📝','label'=>'New Blog Post','route'=>'admin.blog.create'],
                    ['icon'=>'⚡','label'=>'Add Service','route'=>'admin.services.create'],
                    ['icon'=>'👤','label'=>'Add Team Member','route'=>'admin.team.create'],
                    ['icon'=>'📄','label'=>'New Page','route'=>'admin.pages.create'],
                    ['icon'=>'🎨','label'=>'Edit Theme','route'=>'admin.theme'],
                    ['icon'=>'⚙️','label'=>'General Settings','route'=>'admin.settings'],
                ] as $action)
                <a href="{{ route($action['route']) }}" style="display:flex;align-items:center;gap:10px;padding:11px 14px;border-radius:10px;font-size:14px;font-weight:500;color:#334155;transition:all .2s;border:1px solid transparent" onmouseenter="this.style.background='rgba(124,58,237,.05)';this.style.borderColor='rgba(124,58,237,.15)'" onmouseleave="this.style.background='';this.style.borderColor='transparent'">
                    <span style="font-size:1.2rem">{{ $action['icon'] }}</span> {{ $action['label'] }}
                    <span style="margin-left:auto;color:#94a3b8">→</span>
                </a>
                @endforeach
            </div>
        </div>
        <div class="card">
            <div class="card-head"><div class="card-title">📊 Content Summary</div></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:10px">
                @foreach([
                    ['label'=>'Published Services','value'=>\App\Models\Service::where('is_published',true)->count(),'color'=>'var(--s)'],
                    ['label'=>'Published Posts','value'=>\App\Models\BlogPost::where('is_published',true)->count(),'color'=>'var(--success)'],
                    ['label'=>'Testimonials','value'=>\App\Models\Testimonial::where('is_published',true)->count(),'color'=>'var(--warn)'],
                    ['label'=>'Open Positions','value'=>\App\Models\Career::where('is_published',true)->count(),'color'=>'var(--danger)'],
                    ['label'=>'Active FAQs','value'=>\App\Models\Faq::where('is_published',true)->count(),'color'=>'var(--p)'],
                ] as $item)
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f8fafc">
                    <span style="font-size:13px;color:#64748b">{{ $item['label'] }}</span>
                    <span style="font-family:'Syne',sans-serif;font-weight:700;color:{{ $item['color'] }}">{{ $item['value'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection