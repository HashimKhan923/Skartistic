@extends('admin.layouts.app')
@section('title', isset($page) ? 'Edit Page' : 'New Page')

@section('content')
<div style="display:grid;grid-template-columns:1fr 280px;gap:22px;align-items:start">
    <form method="POST" id="pageForm" action="{{ isset($page) ? route('admin.pages.update', $page) : route('admin.pages.store') }}">
        @csrf @if(isset($page)) @method('PUT') @endif

        <div class="card">
            <div class="card-head">
                <div class="card-title">{{ isset($page) ? '✏️ Edit Page' : '📄 New Page' }}</div>
                <a href="{{ route('admin.pages.index') }}" class="btn btn-ghost btn-sm">← Back</a>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Page Title <span class="req">*</span></label>
                    <input class="form-control" type="text" name="title" value="{{ old('title', $page->title ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">
                        Content
                        <span style="font-size:11px;color:#94a3b8;font-weight:400">— HTML supported</span>
                    </label>
                    <textarea class="form-control" name="content" rows="20">{{ old('content', $page->content ?? '') }}</textarea>
                </div>
            </div>
        </div>
        <div style="margin-top:16px;display:flex;gap:10px">
            <button type="submit" class="btn btn-primary">💾 {{ isset($page) ? 'Update Page' : 'Create Page' }}</button>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>

    <div style="position:sticky;top:92px;display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-head"><div class="card-title">⚙️ Page Options</div></div>
            <div class="card-body">
                <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:16px">
                    <label class="form-check"><input type="checkbox" name="is_published" value="1" {{ old('is_published', $page->is_published ?? true) ? 'checked' : '' }} form="pageForm"><span>Published</span></label>
                    <label class="form-check"><input type="checkbox" name="show_in_menu" value="1" {{ old('show_in_menu', $page->show_in_menu ?? false) ? 'checked' : '' }} form="pageForm"><span>Show in navigation menu</span></label>
                </div>
                <div class="form-group">
                    <label class="form-label">Menu Order</label>
                    <input class="form-control" type="number" name="menu_order" value="{{ old('menu_order', $page->menu_order ?? 0) }}" form="pageForm">
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-head"><div class="card-title">🔍 SEO</div></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Meta Title</label>
                    <input class="form-control" type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title ?? '') }}" form="pageForm">
                </div>
                <div class="form-group">
                    <label class="form-label">Meta Description</label>
                    <textarea class="form-control" name="meta_description" rows="3" form="pageForm">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection