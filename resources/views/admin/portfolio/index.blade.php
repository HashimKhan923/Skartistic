@extends('admin.layouts.app')
@section('title','Portfolio Projects')

@section('content')
<div class="card">
    <div class="card-head">
        <div class="card-title">🎨 Portfolio Projects <span style="background:#ede9fe;color:#5b21b6;padding:3px 10px;border-radius:999px;font-size:12px;font-weight:600;margin-left:8px">{{ $portfolios->count() }}</span></div>
        <a href="{{ route('admin.portfolio.create') }}" class="btn btn-primary">+ Add Project</a>
    </div>
    @if($portfolios->count())
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Image</th><th>Project</th><th>Client</th><th>Category</th><th>Featured</th><th>Status</th><th style="text-align:right">Actions</th></tr>
            </thead>
            <tbody>
            @foreach($portfolios as $p)
            <tr>
                <td>
                    @if($p->thumbnail)
                        <img src="{{ asset('storage/'.$p->thumbnail) }}" class="td-img" style="border-radius:8px">
                    @else
                        <div style="width:48px;height:48px;background:linear-gradient(135deg,rgba(124,58,237,.15),rgba(6,182,212,.15));border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.4rem">🎨</div>
                    @endif
                </td>
                <td>
                    <div style="font-weight:600;color:#1e293b">{{ $p->title }}</div>
                    @if($p->tags)
                    <div style="display:flex;gap:4px;margin-top:4px;flex-wrap:wrap">
                        @foreach(array_slice(explode(',',$p->tags),0,3) as $tag)
                        <span style="background:#f1f5f9;color:#64748b;padding:2px 6px;border-radius:4px;font-size:10px">{{ trim($tag) }}</span>
                        @endforeach
                    </div>
                    @endif
                </td>
                <td style="color:#64748b;font-size:14px">{{ $p->client ?? '—' }}</td>
                <td>@if($p->category)<span class="badge badge-purple">{{ $p->category }}</span>@else<span style="color:#94a3b8">—</span>@endif</td>
                <td>@if($p->is_featured)<span class="badge badge-yellow">⭐ Featured</span>@else<span style="color:#94a3b8;font-size:13px">—</span>@endif</td>
                <td><span class="badge {{ $p->is_published ? 'badge-green' : 'badge-yellow' }}">{{ $p->is_published ? 'Live' : 'Draft' }}</span></td>
                <td style="text-align:right">
                    <div style="display:flex;gap:6px;justify-content:flex-end">
                        <a href="{{ route('portfolio.detail', $p->slug) }}" target="_blank" class="btn btn-ghost btn-sm">View</a>
                        <a href="{{ route('admin.portfolio.edit', $p) }}" class="btn btn-ghost btn-sm">Edit</a>
                        <form method="POST" action="{{ route('admin.portfolio.destroy', $p) }}" onsubmit="return confirm('Delete this project?')">
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
        <span class="empty-state-icon">🎨</span>
        <p>No portfolio projects yet. Showcase your best work!</p>
        <a href="{{ route('admin.portfolio.create') }}" class="btn btn-primary">Add First Project</a>
    </div>
    @endif
</div>
@endsection