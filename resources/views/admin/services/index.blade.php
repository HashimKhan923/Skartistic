@extends('admin.layouts.app')
@section('title','Services')

@section('content')
<div class="card">
    <div class="card-head">
        <div class="card-title">⚡ Services <span style="background:#ede9fe;color:#5b21b6;padding:3px 10px;border-radius:999px;font-size:12px;font-weight:600;margin-left:8px">{{ $services->count() }}</span></div>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary">+ Add Service</a>
    </div>
    @if($services->count())
    <div class="table-wrap">
        <table>
            <thead><tr><th>Icon</th><th>Title</th><th>Slug</th><th>Status</th><th style="text-align:right">Actions</th></tr></thead>
            <tbody>
            @foreach($services as $s)
            <tr>
                <td><span class="td-emoji">{{ $s->icon ?? '⚡' }}</span></td>
                <td>
                    <div style="font-weight:600;color:#1e293b">{{ $s->title }}</div>
                    <div style="font-size:12px;color:#94a3b8;margin-top:2px">{{ Str::limit($s->short_description, 60) }}</div>
                </td>
                <td><code style="background:#f1f5f9;padding:3px 8px;border-radius:5px;font-size:12px">/services/{{ $s->slug }}</code></td>
                <td><span class="badge {{ $s->is_published ? 'badge-green' : 'badge-yellow' }}">{{ $s->is_published ? 'Published' : 'Draft' }}</span></td>
                <td style="text-align:right">
                    <div style="display:flex;gap:6px;justify-content:flex-end">
                        <a href="{{ route('service.detail', $s->slug) }}" target="_blank" class="btn btn-ghost btn-sm">View</a>
                        <a href="{{ route('admin.services.edit', $s) }}" class="btn btn-ghost btn-sm">Edit</a>
                        <form method="POST" action="{{ route('admin.services.destroy', $s) }}" onsubmit="return confirm('Delete this service?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="empty-state">
        <span class="empty-state-icon">⚡</span>
        <p>No services yet. Add your first service to get started.</p>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary">Add Service</a>
    </div>
    @endif
</div>
@endsection