@extends('admin.layouts.app')
@section('title', isset($post) ? 'Edit Post' : 'New Post')

@section('content')
<div style="display:grid;grid-template-columns:1fr 300px;gap:22px;align-items:start">
    <form method="POST" id="postForm" action="{{ isset($post) ? route('admin.blog.update', $post) : route('admin.blog.store') }}" enctype="multipart/form-data">
        @csrf @if(isset($post)) @method('PUT') @endif

        <div class="card">
            <div class="card-head">
                <div class="card-title">{{ isset($post) ? '✏️ Edit Post' : '📝 New Post' }}</div>
                <a href="{{ route('admin.blog.index') }}" class="btn btn-ghost btn-sm">← Back</a>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Post Title <span class="req">*</span></label>
                    <input class="form-control" type="text" name="title" value="{{ old('title', $post->title ?? '') }}" required placeholder="Enter post title...">
                </div>
                <div class="form-group">
                    <label class="form-label">Excerpt / Summary</label>
                    <textarea class="form-control" name="excerpt" rows="3" placeholder="Brief description shown in blog listings">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Full Content</label>
                    <textarea class="form-control" name="content" rows="16" placeholder="Write your post content here...">{{ old('content', $post->content ?? '') }}</textarea>
                </div>
            </div>
        </div>
        <div style="margin-top:16px;display:flex;gap:10px">
            <button type="submit" class="btn btn-primary">💾 {{ isset($post) ? 'Update Post' : 'Publish Post' }}</button>
            <a href="{{ route('admin.blog.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>

    <div style="position:sticky;top:92px;display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-head"><div class="card-title">⚙️ Post Settings</div></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Author</label>
                    <input class="form-control" type="text" name="author" value="{{ old('author', $post->author ?? '') }}" placeholder="SK Artistic Team" form="postForm">
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <input class="form-control" type="text" name="category" value="{{ old('category', $post->category ?? '') }}" placeholder="e.g. Design, Development" form="postForm">
                </div>
                <div style="margin-top:4px">
                    <label class="form-check">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $post->is_published ?? true) ? 'checked' : '' }} form="postForm">
                        <span>Published (live on site)</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-head"><div class="card-title">🖼️ Featured Image</div></div>
            <div class="card-body">
                <input type="file" name="featured_image" accept="image/*" class="form-control" form="postForm">
                @if(isset($post) && $post->featured_image)
                <img src="{{ asset('storage/'.$post->featured_image) }}" class="img-preview" style="width:100%;height:160px;object-fit:cover;margin-top:10px;border-radius:10px">
                @endif
            </div>
        </div>
    </div>
</div>
@endsection