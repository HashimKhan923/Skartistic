@extends('admin.layouts.app')
@section('title','Contact Messages')

@section('content')
<div class="card">
    <div class="card-head">
        <div class="card-title">📬 Contact Messages
            @php $unread = \App\Models\Contact::where('is_read', false)->count(); @endphp
            @if($unread)<span class="badge badge-blue" style="margin-left:8px">{{ $unread }} new</span>@endif
        </div>
        <a href="{{ route('contact') }}" target="_blank" class="btn btn-ghost btn-sm">View Contact Page</a>
    </div>
    @if($contacts->count())
    <div class="table-wrap">
        <table>
            <thead><tr><th>From</th><th>Subject</th><th>Service</th><th>Budget</th><th>Date</th><th>Status</th><th style="text-align:right">Actions</th></tr></thead>
            <tbody>
            @foreach($contacts as $contact)
            <tr style="{{ !$contact->is_read ? 'background:#fefcff' : '' }}">
                <td>
                    <div style="font-weight:{{ !$contact->is_read ? '700' : '600' }};color:#1e293b">{{ $contact->name }}</div>
                    <a href="mailto:{{ $contact->email }}" style="font-size:12px;color:var(--p)">{{ $contact->email }}</a>
                </td>
                <td style="font-size:13px;color:#64748b;max-width:220px">{{ $contact->subject ?: '—' }}</td>
                <td>@if($contact->service)<span class="badge badge-purple">{{ $contact->service }}</span>@else<span style="color:#94a3b8">—</span>@endif</td>
                <td>@if($contact->budget)<span class="badge badge-green">{{ $contact->budget }}</span>@else<span style="color:#94a3b8">—</span>@endif</td>
                <td style="font-size:12px;color:#94a3b8;white-space:nowrap">{{ $contact->created_at->diffForHumans() }}</td>
                <td><span class="badge {{ $contact->is_read ? 'badge-green' : 'badge-blue' }}">{{ $contact->is_read ? 'Read' : 'New' }}</span></td>
                <td style="text-align:right">
                    <div style="display:flex;gap:6px;justify-content:flex-end">
                        <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-ghost btn-sm">Open</a>
                        <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" onsubmit="return confirm('Delete this message?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm">Delete</button></form>
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $contacts->links() }}</div>
    @else
    <div class="empty-state">
        <span class="empty-state-icon">📬</span>
        <p>No messages yet. Share the <a href="{{ route('contact') }}" style="color:var(--p);font-weight:600" target="_blank">Contact page</a> to start hearing from visitors!</p>
    </div>
    @endif
</div>
@endsection
