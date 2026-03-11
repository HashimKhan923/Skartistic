@extends('admin.layouts.app')
@section('title', isset($page) ? 'Edit Page' : 'Add Page')
@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">{{ isset($page) ? '✏️ Edit Page' : '➕ New Page' }}</div>
        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">← Back</a>
    </div>
    <form method="POST" action="{{ isset($page) ? route('admin.pages.update', $page) : route('admin.pages.store') }}">
        @csrf
        @if(isset($page)) @method('PUT') @endif
        <div class="form-group">
            <label>Page Title *</label>
            <input type="text" name="title" value="{{ old('title', $page->title ?? '') }}" required>
        </div>
        <div class="form-group">
            <label>Content (HTML supported)</label>
            <textarea name="content" rows="16">{{ old('content', $page->content ?? '') }}</textarea>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label>Meta Title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title ?? '') }}">
            </div>
            <div class="form-group">
                <label>Meta Description</label>
                <input type="text" name="meta_description" value="{{ old('meta_description', $page->meta_description ?? '') }}">
            </div>
            <div class="form-group">
                <label>Menu Order (for sorting)</label>
                <input type="number" name="menu_order" value="{{ old('menu_order', $page->menu_order ?? 0) }}">
            </div>
        </div>
        <div style="display:flex;gap:24px;margin-bottom:20px;">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', $page->is_published ?? true) ? 'checked' : '' }}>
                Published
            </label>
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                <input type="checkbox" name="show_in_menu" value="1" {{ old('show_in_menu', $page->show_in_menu ?? false) ? 'checked' : '' }}>
                Show in Navigation Menu
            </label>
        </div>
        <button type="submit" class="btn btn-primary">💾 Save Page</button>
    </form>
</div>
@endsection