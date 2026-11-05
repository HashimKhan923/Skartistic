@extends('admin.layouts.app')
@section('title','Gallery')

@section('content')
<style>
.gl-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:18px;padding:24px}
.gl-item{border:1.5px solid var(--a-line);border-radius:14px;overflow:hidden;display:flex;flex-direction:column}
.gl-item img{width:100%;aspect-ratio:4/5;object-fit:cover;display:block}
.gl-body{padding:12px;display:flex;flex-direction:column;gap:8px}
.gl-row{display:flex;align-items:center;justify-content:space-between;gap:8px}
.gl-check{display:flex;align-items:center;gap:6px;font-size:13px;color:var(--a-text)}
.gl-warn{color:#dc2626;font-size:13px;font-weight:600;margin-top:8px;display:none}
</style>

<div class="card" style="margin-bottom:20px">
    <div class="card-head"><div class="card-title">🖼️ Upload to Gallery</div></div>
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-error" style="color:#dc2626;font-weight:600;margin-bottom:12px">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data" id="glForm">
            @csrf
            <input type="file" name="images[]" id="glFiles" accept="image/jpeg,image/png,image/webp" multiple required class="form-control">
            <div class="form-hint">Images must be 4:5, for example 1080 × 1350 pixels. JPG, PNG or WebP, up to 10 MB each and 20 at a time.</div>
            <div class="gl-warn" id="glWarn" role="alert"></div>
            <button class="btn btn-primary" id="glSubmit" style="margin-top:12px">Upload Images</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-head">
        <div class="card-title">Gallery Images <span style="background:var(--a-tint);color:var(--p);padding:3px 10px;border-radius:999px;font-size:12px;font-weight:600;margin-left:8px">{{ $images->count() }}</span></div>
        <a href="{{ route('gallery') }}" target="_blank" rel="noopener" class="btn btn-ghost btn-sm">View Page ↗</a>
    </div>
    @if($images->count())
    <div class="gl-grid">
        @foreach($images as $image)
        <div class="gl-item">
            <img src="{{ asset('storage/'.$image->image) }}" alt="{{ $image->caption }}" loading="lazy">
            <div class="gl-body">
                <form method="POST" action="{{ route('admin.gallery.update', $image) }}" style="display:flex;flex-direction:column;gap:8px">
                    @csrf @method('PUT')
                    <input class="form-control" name="caption" value="{{ $image->caption }}" placeholder="Caption (optional)" aria-label="Caption">
                    <input class="form-control" type="number" name="sort_order" value="{{ $image->sort_order }}" aria-label="Sort order" title="Lower numbers show first">
                    <label class="gl-check"><input type="checkbox" name="is_published" value="1" @checked($image->is_published)> Show on site</label>
                    <button class="btn btn-ghost btn-sm">Save</button>
                </form>
                <form method="POST" action="{{ route('admin.gallery.destroy', $image) }}" onsubmit="return confirm('Delete this image?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" style="width:100%">Delete</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state">
        <span class="empty-state-icon">🖼️</span>
        <p>No gallery images yet. Upload your first 4:5 images above.</p>
    </div>
    @endif
</div>

<script>
// Same tolerance as the server rule, so the visitor learns before a long upload
document.getElementById('glFiles').addEventListener('change', function () {
    var warn = document.getElementById('glWarn'), submit = document.getElementById('glSubmit'), bad = [], left = this.files.length;
    warn.style.display = 'none'; submit.disabled = false;
    Array.prototype.forEach.call(this.files, function (file) {
        var url = URL.createObjectURL(file), img = new Image();
        img.onload = function () {
            if (Math.abs(4 / 5 - img.naturalWidth / img.naturalHeight) > 1 / (Math.max(img.naturalWidth, img.naturalHeight) + 1)) bad.push(file.name + ' (' + img.naturalWidth + ' × ' + img.naturalHeight + ')');
            done(url);
        };
        img.onerror = function () { bad.push(file.name); done(url); };
        img.src = url;
    });
    function done(url) {
        URL.revokeObjectURL(url);
        if (--left) return;
        if (bad.length) { warn.textContent = 'Not 4:5: ' + bad.join(', '); warn.style.display = 'block'; submit.disabled = true; }
    }
});
</script>
@endsection
