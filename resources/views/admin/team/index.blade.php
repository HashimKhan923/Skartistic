@extends('admin.layouts.app')
@section('title','Team Members')
@section('content')
<div class="card">
    <div class="card-head">
        <div class="card-title">👥 Team Members <span style="background:#ede9fe;color:#5b21b6;padding:3px 10px;border-radius:999px;font-size:12px;font-weight:600;margin-left:8px">{{ $members->count() }}</span></div>
        <a href="{{ route('admin.team.create') }}" class="btn btn-primary">+ Add Member</a>
    </div>
    @if($members->count())
    <div class="table-wrap">
        <table>
            <thead><tr><th>Photo</th><th>Name</th><th>Position</th><th>Social</th><th>Status</th><th style="text-align:right">Actions</th></tr></thead>
            <tbody>
            @foreach($members as $m)
            <tr>
                <td>
                    @if($m->photo)
                        <img src="{{ asset('storage/'.$m->photo) }}" class="td-img">
                    @else
                        <div style="width:40px;height:40px;background:linear-gradient(135deg,rgba(124,58,237,.2),rgba(6,182,212,.2));border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;font-weight:700;color:#7c3aed">{{ strtoupper(substr($m->name,0,1)) }}</div>
                    @endif
                </td>
                <td><div style="font-weight:600;color:#1e293b">{{ $m->name }}</div></td>
                <td style="color:#64748b">{{ $m->position }}</td>
                <td>
                    <div style="display:flex;gap:6px">
                        @if($m->linkedin)<a href="{{ $m->linkedin }}" target="_blank" style="font-size:11px;background:#dbeafe;color:#1d4ed8;padding:3px 8px;border-radius:5px;font-weight:600">LinkedIn</a>@endif
                        @if($m->instagram)<a href="{{ $m->instagram }}" target="_blank" style="font-size:11px;background:#fce7f3;color:#be185d;padding:3px 8px;border-radius:5px;font-weight:600">Instagram</a>@endif
                    </div>
                </td>
                <td><span class="badge {{ $m->is_published ? 'badge-green' : 'badge-yellow' }}">{{ $m->is_published ? 'Visible' : 'Hidden' }}</span></td>
                <td style="text-align:right">
                    <div style="display:flex;gap:6px;justify-content:flex-end">
                        <a href="{{ route('admin.team.edit', $m) }}" class="btn btn-ghost btn-sm">Edit</a>
                        <form method="POST" action="{{ route('admin.team.destroy', $m) }}" onsubmit="return confirm('Remove this team member?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm">Remove</button></form>
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="empty-state"><span class="empty-state-icon">👥</span><p>No team members yet.</p><a href="{{ route('admin.team.create') }}" class="btn btn-primary">Add First Member</a></div>
    @endif
</div>
@endsection