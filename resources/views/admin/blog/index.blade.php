@extends('admin.layouts.app')
@section('title','Blog Posts')
@section('content')
<div class="card">
    <div class="card-head">
        <div class="card-title">✏️ Blog Posts <span style="background:var(--a-tint);color:var(--p);padding:3px 10px;border-radius:999px;font-size:12px;font-weight:600;margin-left:8px">{{ $posts->count() }}</span></div>
        <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">+ New Post</a>
    </div>
    @if($posts->count())
    <div class="table-wrap">
        <table>
            <thead><tr><th>Image</th><th>Title</th><th>Author</th><th>Category</th><th>Status</th><th>Date</th><th style="text-align:right">Actions</th></tr></thead>
            <tbody>
            @foreach($posts as $p)
            <tr>
                <td>@if($p->featured_image)<img src="{{ asset('storage/'.$p->featured_image) }}" class="td-img">@else<div style="width:40px;height:40px;background:var(--a-soft);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem">📝</div>@endif</td>
                <td><div style="font-weight:600;color:var(--a-text);max-width:280px">{{ $p->title }}</div><div style="font-size:12px;color:var(--a-dim);margin-top:2px">{{ Str::limit($p->excerpt ?? '', 60) }}</div></td>
                <td style="color:var(--a-muted);font-size:14px">{{ $p->author ?? '—' }}</td>
                <td>@if($p->category)<span class="badge badge-purple">{{ $p->category }}</span>@else<span style="color:var(--a-dim)">—</span>@endif</td>
                <td><span class="badge {{ $p->is_published ? 'badge-green' : 'badge-yellow' }}">{{ $p->is_published ? 'Live' : 'Draft' }}</span></td>
                <td style="font-size:13px;color:var(--a-muted);white-space:nowrap">{{ $p->published_at ? $p->published_at->format('M d, Y') : '—' }}</td>
                <td style="text-align:right">
                    <div style="display:flex;gap:6px;justify-content:flex-end">
                        @if($p->is_published)<a href="{{ route('blog.post', $p->slug) }}" target="_blank" class="btn btn-ghost btn-sm">View</a>@endif
                        <a href="{{ route('admin.blog.edit', $p) }}" class="btn btn-ghost btn-sm">Edit</a>
                        <form method="POST" action="{{ route('admin.blog.destroy', $p) }}" onsubmit="return confirm('Delete this post?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm">Delete</button></form>
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="empty-state"><span class="empty-state-icon">✏️</span><p>No blog posts yet. Start writing!</p><a href="{{ route('admin.blog.create') }}" class="btn btn-primary">Write First Post</a></div>
    @endif
</div>
@endsection