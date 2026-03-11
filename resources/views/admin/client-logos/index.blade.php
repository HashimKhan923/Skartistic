@extends('admin.layouts.app')
@section('title','Client Logos')

@section('content')
<div class="card">
    <div class="card-head">
        <div class="card-title">🏢 Client Logos <span style="background:#ede9fe;color:#5b21b6;padding:3px 10px;border-radius:999px;font-size:12px;font-weight:600;margin-left:8px">{{ $logos->count() }}</span></div>
        <a href="{{ route('admin.client-logos.create') }}" class="btn btn-primary">+ Add Logo</a>
    </div>
    @if($logos->count())
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;padding:24px">
        @foreach($logos as $logo)
        <div style="border:1.5px solid #e8ecf0;border-radius:14px;padding:20px;text-align:center;transition:all .2s" onmouseenter="this.style.borderColor='rgba(124,58,237,.3)';this.style.boxShadow='0 8px 25px rgba(124,58,237,.08)'" onmouseleave="this.style.borderColor='#e8ecf0';this.style.boxShadow=''">
            <div style="height:60px;display:flex;align-items:center;justify-content:center;margin-bottom:12px">
                <img src="{{ asset('storage/'.$logo->logo) }}" alt="{{ $logo->name }}" style="max-height:50px;max-width:130px;object-fit:contain">
            </div>
            <div style="font-weight:600;font-size:13px;color:#374151;margin-bottom:6px">{{ $logo->name }}</div>
            <span class="badge {{ $logo->is_published ? 'badge-green' : 'badge-yellow' }}" style="margin-bottom:10px;display:inline-flex">{{ $logo->is_published ? 'Shown' : 'Hidden' }}</span>
            <div style="display:flex;gap:6px;justify-content:center">
                <a href="{{ route('admin.client-logos.edit', $logo) }}" class="btn btn-ghost btn-sm">Edit</a>
                <form method="POST" action="{{ route('admin.client-logos.destroy', $logo) }}" onsubmit="return confirm('Remove this logo?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm">×</button></form>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state">
        <span class="empty-state-icon">🏢</span>
        <p>No client logos yet. Add logos to show in the marquee on your homepage.</p>
        <a href="{{ route('admin.client-logos.create') }}" class="btn btn-primary">Add First Logo</a>
    </div>
    @endif
</div>
@endsection