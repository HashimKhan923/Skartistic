@extends('admin.layouts.app')
@section('title', isset($faq) ? 'Edit FAQ' : 'Add FAQ')
@section('content')
<style>
.main .content { box-sizing:border-box; width:100%; overflow-x:hidden; }
.sg  { display:grid; grid-template-columns:1fr 1fr; gap:22px; }
.sg .full { grid-column:1/-1; }
@media(max-width:900px){ .sg { grid-template-columns:1fr; } }
</style>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px">
    <div>
        <p style="font-size:13px;color:var(--a-muted);margin:2px 0 0">Admin Panel / FAQs</p>
    </div>
    <a href="{{ route('admin.faqs.index') }}" class="btn btn-ghost btn-sm">← Back</a>
</div>


<form method="POST" action="{{ isset($faq) ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}">
    @csrf @if(isset($faq)) @method('PUT') @endif

    <div class="sg">
        <div class="card full">
            <div class="card-head"><div class="card-title">❓ FAQ Content</div></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Question <span style="color:#ef4444">*</span></label>
                    <input class="form-control" type="text" name="question"
                           value="{{ old('question', $faq->question ?? '') }}"
                           required placeholder="How long does a project take?">
                </div>
                <div class="form-group">
                    <label class="form-label">Answer <span style="color:#ef4444">*</span></label>
                    <textarea class="form-control" name="answer" rows="6"
                              required placeholder="Write the detailed answer here...">{{ old('answer', $faq->answer ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-head"><div class="card-title">⚙️ Options</div></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Sort Order</label>
                    <input class="form-control" type="number" name="sort_order"
                           value="{{ old('sort_order', $faq->sort_order ?? 0) }}">
                    <div class="form-hint">Lower = appears first</div>
                </div>
                <label class="form-check" style="margin-top:8px">
                    <input type="checkbox" name="is_published" value="1"
                           {{ old('is_published', $faq->is_published ?? true) ? 'checked' : '' }}>
                    <span>Published (visible on site)</span>
                </label>
            </div>
        </div>

        <div class="card" style="grid-column:2">
            <div class="card-head"><div class="card-title">💡 Tips</div></div>
            <div class="card-body">
                <p style="font-size:13px;color:var(--a-muted);line-height:1.7;margin:0">
                    • Keep questions short and clear<br>
                    • Answers can include full sentences<br>
                    • Use sort order to control sequence<br>
                    • Unpublished FAQs won't show on site
                </p>
            </div>
        </div>
    </div>

    <div style="margin-top:22px;display:flex;gap:10px">
        <button type="submit" class="btn btn-primary">
            💾 {{ isset($faq) ? 'Update FAQ' : 'Create FAQ' }}
        </button>
        <a href="{{ route('admin.faqs.index') }}" class="btn btn-ghost">Cancel</a>
    </div>
</form>
@endsection