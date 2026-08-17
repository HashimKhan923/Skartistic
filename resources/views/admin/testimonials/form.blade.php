@extends('admin.layouts.app')
@section('title', isset($testimonial) ? 'Edit Testimonial' : 'Add Testimonial')

@section('content')
<div style="display:grid;grid-template-columns:1fr 300px;gap:22px;align-items:start">
    <form method="POST" id="testiForm" action="{{ isset($testimonial) ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}" enctype="multipart/form-data">
        @csrf @if(isset($testimonial)) @method('PUT') @endif

        <div class="card">
            <div class="card-head">
                <div class="card-title">{{ isset($testimonial) ? '✏️ Edit Testimonial' : '➕ Add Testimonial' }}</div>
                <a href="{{ route('admin.testimonials.index') }}" class="btn btn-ghost btn-sm">← Back</a>
            </div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Client Name <span class="req">*</span></label>
                        <input class="form-control" type="text" name="client_name" value="{{ old('client_name', $testimonial->client_name ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Client Position / Company</label>
                        <input class="form-control" type="text" name="client_position" value="{{ old('client_position', $testimonial->client_position ?? '') }}" placeholder="Marketing Manager, Acme Co.">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Review <span class="req">*</span></label>
                    <textarea class="form-control" name="review" rows="5" required placeholder="What did the client say about working with us?">{{ old('review', $testimonial->review ?? '') }}</textarea>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Rating <span class="req">*</span></label>
                        <select class="form-control" name="rating" required>
                            @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ old('rating', $testimonial->rating ?? 5) == $i ? 'selected' : '' }}>{{ str_repeat('★', $i) }} ({{ $i }})</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input class="form-control" type="number" name="sort_order" value="{{ old('sort_order', $testimonial->sort_order ?? 0) }}">
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-top:16px;display:flex;gap:10px">
            <button type="submit" class="btn btn-primary">💾 {{ isset($testimonial) ? 'Update Testimonial' : 'Add Testimonial' }}</button>
            <a href="{{ route('admin.testimonials.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>

    <div style="position:sticky;top:92px;display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-head"><div class="card-title">🖼️ Photo</div></div>
            <div class="card-body">
                @if(isset($testimonial) && $testimonial->photo)
                <img src="{{ asset('storage/'.$testimonial->photo) }}" style="width:100%;height:180px;object-fit:cover;border-radius:12px;margin-bottom:12px">
                @endif
                <input type="file" name="photo" accept="image/*" class="form-control" form="testiForm">
            </div>
        </div>
        <div class="card">
            <div class="card-head"><div class="card-title">👁️ Visibility</div></div>
            <div class="card-body">
                <label class="form-check">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $testimonial->is_published ?? true) ? 'checked' : '' }} form="testiForm">
                    <span>Show on website</span>
                </label>
            </div>
        </div>
    </div>
</div>
@endsection
