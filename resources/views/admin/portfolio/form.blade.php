@extends('admin.layouts.app')
@section('title', isset($portfolio) ? 'Edit Project' : 'Add Project')

@section('styles')
<style>
.img-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(100px,1fr));gap:8px;margin-top:10px}
.img-thumb-wrap{position:relative;border-radius:8px;overflow:hidden;aspect-ratio:1}
.img-thumb-wrap img{width:100%;height:100%;object-fit:cover}
.img-remove{position:absolute;top:4px;right:4px;width:22px;height:22px;background:rgba(239,68,68,.9);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;cursor:pointer;border:none;line-height:1}
</style>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:1fr 300px;gap:22px;align-items:start">
    <form method="POST" id="pForm" action="{{ isset($portfolio) ? route('admin.portfolio.update', $portfolio) : route('admin.portfolio.store') }}" enctype="multipart/form-data">
        @csrf @if(isset($portfolio)) @method('PUT') @endif

        <div class="card">
            <div class="card-head">
                <div class="card-title">{{ isset($portfolio) ? '✏️ Edit Project' : '🎨 New Project' }}</div>
                <a href="{{ route('admin.portfolio.index') }}" class="btn btn-ghost btn-sm">← Back</a>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Project Title <span class="req">*</span></label>
                    <input class="form-control" type="text" name="title" value="{{ old('title', $portfolio->title ?? '') }}" required placeholder="e.g. SK Mart E-commerce Platform">
                </div>
                <div class="form-group">
                    <label class="form-label">Short Description</label>
                    <textarea class="form-control" name="description" rows="3" placeholder="Brief description shown in project cards">{{ old('description', $portfolio->description ?? '') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Full Case Study Content <span style="font-size:11px;color:#94a3b8">— HTML supported</span></label>
                    <textarea class="form-control" name="content" rows="14" placeholder="Describe the project, challenges, solutions, results...">{{ old('content', $portfolio->content ?? '') }}</textarea>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Client Name</label>
                        <input class="form-control" type="text" name="client" value="{{ old('client', $portfolio->client ?? '') }}" placeholder="Client or company name">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select class="form-control" name="category">
                            <option value="">Select category...</option>
                            @foreach(['Web Development','Mobile App','UI/UX Design','Graphic Design','E-Commerce','Branding','Other'] as $cat)
                            <option value="{{ $cat }}" {{ old('category', $portfolio->category ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Technologies / Tags</label>
                        <input class="form-control" type="text" name="tags" value="{{ old('tags', $portfolio->tags ?? '') }}" placeholder="Laravel, React, Figma (comma separated)">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Completion Date</label>
                        <input class="form-control" type="date" name="completed_at" value="{{ old('completed_at', isset($portfolio->completed_at) ? $portfolio->completed_at->format('Y-m-d') : '') }}">
                    </div>
                    <div class="form-group" style="grid-column:1/-1">
                        <label class="form-label">Live Project URL</label>
                        <input class="form-control" type="url" name="project_url" value="{{ old('project_url', $portfolio->project_url ?? '') }}" placeholder="https://example.com">
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-top:16px;display:flex;gap:10px">
            <button type="submit" class="btn btn-primary">💾 {{ isset($portfolio) ? 'Update Project' : 'Add Project' }}</button>
            <a href="{{ route('admin.portfolio.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>

    {{-- Sidebar --}}
    <div style="position:sticky;top:92px;display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-head"><div class="card-title">⚙️ Options</div></div>
            <div class="card-body">
                <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:16px">
                    <label class="form-check"><input type="checkbox" name="is_published" value="1" {{ old('is_published', $portfolio->is_published ?? true) ? 'checked' : '' }} form="pForm"><span>Published (visible)</span></label>
                    <label class="form-check"><input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $portfolio->is_featured ?? false) ? 'checked' : '' }} form="pForm"><span>Featured on homepage</span></label>
                </div>
                <div class="form-group">
                    <label class="form-label">Sort Order</label>
                    <input class="form-control" type="number" name="sort_order" value="{{ old('sort_order', $portfolio->sort_order ?? 0) }}" form="pForm">
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-head"><div class="card-title">🖼️ Thumbnail</div></div>
            <div class="card-body">
                @if(isset($portfolio) && $portfolio->thumbnail)
                <img src="{{ asset('storage/'.$portfolio->thumbnail) }}" style="width:100%;height:140px;object-fit:cover;border-radius:10px;margin-bottom:10px">
                @endif
                <input type="file" name="thumbnail" accept="image/*" class="form-control" form="pForm">
                <div class="form-hint">Main cover image for the project card</div>
            </div>
        </div>

        <div class="card">
            <div class="card-head"><div class="card-title">📸 Gallery Images</div></div>
            <div class="card-body">
                @if(isset($portfolio) && $portfolio->images && count($portfolio->images))
                <div class="img-grid">
                    @foreach($portfolio->images as $img)
                    <div class="img-thumb-wrap">
                        <img src="{{ asset('storage/'.$img) }}" alt="">
                    </div>
                    @endforeach
                </div>
                <div class="form-hint" style="margin-top:8px">{{ count($portfolio->images) }} image(s) uploaded</div>
                @endif
                <input type="file" name="images[]" accept="image/*" multiple class="form-control" style="{{ (isset($portfolio) && $portfolio->images) ? 'margin-top:10px' : '' }}" form="pForm">
                <div class="form-hint">Select multiple images for the gallery</div>
            </div>
        </div>
    </div>
</div>
@endsection