@extends('admin.layouts.app')
@section('title', isset($member) ? 'Edit Member' : 'Add Member')

@section('content')
<div style="display:grid;grid-template-columns:1fr 300px;gap:22px;align-items:start">
    <form method="POST" id="teamForm" action="{{ isset($member) ? route('admin.team.update', $member) : route('admin.team.store') }}" enctype="multipart/form-data">
        @csrf @if(isset($member)) @method('PUT') @endif

        <div class="card">
            <div class="card-head">
                <div class="card-title">{{ isset($member) ? '✏️ Edit Member' : '➕ Add Team Member' }}</div>
                <a href="{{ route('admin.team.index') }}" class="btn btn-ghost btn-sm">← Back</a>
            </div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Full Name <span class="req">*</span></label>
                        <input class="form-control" type="text" name="name" value="{{ old('name', $member->name ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Position / Role <span class="req">*</span></label>
                        <input class="form-control" type="text" name="position" value="{{ old('position', $member->position ?? '') }}" required placeholder="UI/UX Designer">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Bio</label>
                    <textarea class="form-control" name="bio" rows="4" placeholder="Short biography...">{{ old('bio', $member->bio ?? '') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Sort Order</label>
                    <input class="form-control" type="number" name="sort_order" value="{{ old('sort_order', $member->sort_order ?? 0) }}" style="max-width:120px">
                </div>
            </div>
        </div>
        <div style="margin-top:16px;display:flex;gap:10px">
            <button type="submit" class="btn btn-primary">💾 {{ isset($member) ? 'Update Member' : 'Add Member' }}</button>
            <a href="{{ route('admin.team.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>

    <div style="position:sticky;top:92px;display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-head"><div class="card-title">🖼️ Photo</div></div>
            <div class="card-body">
                @if(isset($member) && $member->photo)
                <img src="{{ asset('storage/'.$member->photo) }}" style="width:100%;height:180px;object-fit:cover;border-radius:12px;margin-bottom:12px">
                @endif
                <input type="file" name="photo" accept="image/*" class="form-control" form="teamForm">
            </div>
        </div>
        <div class="card">
            <div class="card-head"><div class="card-title">🔗 Social Links</div></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">LinkedIn URL</label>
                    <input class="form-control" type="url" name="linkedin" value="{{ old('linkedin', $member->linkedin ?? '') }}" placeholder="https://linkedin.com/in/..." form="teamForm">
                </div>
                <div class="form-group">
                    <label class="form-label">Twitter URL</label>
                    <input class="form-control" type="url" name="twitter" value="{{ old('twitter', $member->twitter ?? '') }}" placeholder="https://twitter.com/..." form="teamForm">
                </div>
                <div class="form-group">
                    <label class="form-label">Instagram URL</label>
                    <input class="form-control" type="url" name="instagram" value="{{ old('instagram', $member->instagram ?? '') }}" placeholder="https://instagram.com/..." form="teamForm">
                </div>
                <label class="form-check">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $member->is_published ?? true) ? 'checked' : '' }} form="teamForm">
                    <span>Show on website</span>
                </label>
            </div>
        </div>
    </div>
</div>
@endsection