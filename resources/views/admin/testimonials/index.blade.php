@extends('admin.layouts.app')
@section('title','Testimonials')
@section('content')
<div class="card">
    <div class="card-head">
        <div class="card-title">⭐ Testimonials</div>
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">+ Add Review</a>
    </div>
    @if($testimonials->count())
    <div class="table-wrap">
        <table>
            <thead><tr><th>Photo</th><th>Client</th><th>Review</th><th>Rating</th><th>Status</th><th style="text-align:right">Actions</th></tr></thead>
            <tbody>
            @foreach($testimonials as $t)
            <tr>
                <td><div style="width:40px;height:40px;background:linear-gradient(135deg,rgba(124,58,237,.2),rgba(6,182,212,.2));border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;color:#7c3aed;overflow:hidden">@if($t->photo)<img src="{{ asset('storage/'.$t->photo) }}" style="width:100%;height:100%;object-fit:cover">@else{{ strtoupper(substr($t->client_name,0,1)) }}@endif</div></td>
                <td><div style="font-weight:600">{{ $t->client_name }}</div><div style="font-size:12px;color:#94a3b8">{{ $t->client_position }}</div></td>
                <td><div style="font-size:13px;color:#64748b;max-width:300px">{{ Str::limit($t->review, 80) }}</div></td>
                <td><span style="color:#f59e0b">{{ str_repeat('★', $t->rating) }}</span></td>
                <td><span class="badge {{ $t->is_published ? 'badge-green' : 'badge-yellow' }}">{{ $t->is_published ? 'Shown' : 'Hidden' }}</span></td>
                <td style="text-align:right"><div style="display:flex;gap:6px;justify-content:flex-end"><a href="{{ route('admin.testimonials.edit', $t) }}" class="btn btn-ghost btn-sm">Edit</a><form method="POST" action="{{ route('admin.testimonials.destroy', $t) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm">Delete</button></form></div></td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="empty-state"><span class="empty-state-icon">⭐</span><p>No testimonials yet.</p><a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">Add First Review</a></div>
    @endif
</div>
@endsection