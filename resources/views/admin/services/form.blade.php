@extends('admin.layouts.app')
@section('title', isset($service) ? 'Edit Service' : 'Add Service')

@section('content')
<div style="display:grid;grid-template-columns:1fr 320px;gap:22px;align-items:start">
    <form method="POST" action="{{ isset($service) ? route('admin.services.update', $service) : route('admin.services.store') }}" enctype="multipart/form-data">
        @csrf @if(isset($service)) @method('PUT') @endif

        <div class="card">
            <div class="card-head">
                <div class="card-title">{{ isset($service) ? '✏️ Edit Service' : '➕ Add Service' }}</div>
                <a href="{{ route('admin.services.index') }}" class="btn btn-ghost btn-sm">← Back</a>
            </div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Service Title <span class="req">*</span></label>
                        <input class="form-control" type="text" name="title" value="{{ old('title', $service->title ?? '') }}" required placeholder="Website Development">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Icon (emoji) <span class="req">*</span></label>
                        <input class="form-control" type="text" name="icon" value="{{ old('icon', $service->icon ?? '') }}" placeholder="🌐">
                        <div class="form-hint">Paste any emoji or use text like "🌐"</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Short Description</label>
                    <textarea class="form-control" name="short_description" rows="2" placeholder="Brief description shown on cards">{{ old('short_description', $service->short_description ?? '') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Full Content</label>
                    <textarea class="form-control" name="content" rows="10" placeholder="Detailed service description...">{{ old('content', $service->content ?? '') }}</textarea>
                </div>
            </div>
        </div>
        <div style="margin-top:16px;display:flex;gap:10px">
            <button type="submit" class="btn btn-primary">💾 {{ isset($service) ? 'Update Service' : 'Create Service' }}</button>
            <a href="{{ route('admin.services.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>

    <div style="position:sticky;top:92px;display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-head"><div class="card-title">⚙️ Options</div></div>
            <div class="card-body">
                <label class="form-check">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $service->is_published ?? true) ? 'checked' : '' }} form="{{ isset($service) ? 'service-form' : 'service-form' }}">
                    <span>Published (visible on site)</span>
                </label>
                <div style="margin-top:16px">
                    <label class="form-label">Sort Order</label>
                    <input class="form-control" type="number" name="sort_order" value="{{ old('sort_order', $service->sort_order ?? 0) }}">
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-head"><div class="card-title">🖼️ Featured Image</div></div>
            <div class="card-body">
                <input type="file" name="image" accept="image/*" class="form-control">
                @if(isset($service) && $service->image)
                <img src="{{ asset('storage/'.$service->image) }}" class="img-preview">
                @endif
            </div>
        </div>
    </div>
</div>
<script>
// move checkboxes and file inputs into form
document.querySelectorAll('input[name=is_published], input[name=sort_order], input[name=image]').forEach(el => {
    document.querySelector('form').appendChild(el.closest('.form-group, label') || el);
});
</script>
@endsection