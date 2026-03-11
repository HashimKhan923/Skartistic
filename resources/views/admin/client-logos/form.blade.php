@extends('admin.layouts.app')
@section('title', isset($logo) ? 'Edit Logo' : 'Add Logo')

@section('content')
<div style="max-width:600px">
    <form method="POST" action="{{ isset($logo) ? route('admin.client-logos.update', $logo) : route('admin.client-logos.store') }}" enctype="multipart/form-data">
        @csrf @if(isset($logo)) @method('PUT') @endif
        <div class="card">
            <div class="card-head">
                <div class="card-title">{{ isset($logo) ? '✏️ Edit Client Logo' : '🏢 Add Client Logo' }}</div>
                <a href="{{ route('admin.client-logos.index') }}" class="btn btn-ghost btn-sm">← Back</a>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Client / Company Name <span class="req">*</span></label>
                    <input class="form-control" type="text" name="name" value="{{ old('name', $logo->name ?? '') }}" required placeholder="Acme Corp">
                </div>
                <div class="form-group">
                    <label class="form-label">Logo Image <span class="req">{{ isset($logo) ? '' : '*' }}</span></label>
                    @if(isset($logo) && $logo->logo)
                    <div style="background:#f8fafc;border:1px solid #e8ecf0;border-radius:10px;padding:16px;display:flex;align-items:center;justify-content:center;margin-bottom:12px;height:80px">
                        <img src="{{ asset('storage/'.$logo->logo) }}" alt="{{ $logo->name }}" style="max-height:60px;max-width:180px;object-fit:contain">
                    </div>
                    @endif
                    <input type="file" name="logo" accept="image/*" class="form-control" {{ !isset($logo) ? 'required' : '' }}>
                    <div class="form-hint">PNG with transparent background recommended. Max 2MB.</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Website URL (optional)</label>
                    <input class="form-control" type="url" name="website_url" value="{{ old('website_url', $logo->website_url ?? '') }}" placeholder="https://clientwebsite.com">
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input class="form-control" type="number" name="sort_order" value="{{ old('sort_order', $logo->sort_order ?? 0) }}">
                    </div>
                    <div style="display:flex;align-items:end;padding-bottom:22px">
                        <label class="form-check"><input type="checkbox" name="is_published" value="1" {{ old('is_published', $logo->is_published ?? true) ? 'checked' : '' }}><span>Show in marquee</span></label>
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-top:16px;display:flex;gap:10px">
            <button type="submit" class="btn btn-primary">💾 {{ isset($logo) ? 'Update Logo' : 'Add Logo' }}</button>
            <a href="{{ route('admin.client-logos.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>
@endsection