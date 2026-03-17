@extends('admin.layouts.app')
@section('title', isset($plan) ? 'Edit Plan' : 'Add Plan')
@section('content')
<style>
.main .content { box-sizing:border-box; width:100%; overflow-x:hidden; }
.sg  { display:grid; grid-template-columns:1fr 1fr; gap:22px; }
.sg .full { grid-column:1/-1; }
.g2col { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
@media(max-width:900px){ .sg,.g2col { grid-template-columns:1fr; } }
</style>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px">
    <div>
        <h2 style="font-size:20px;font-weight:800;color:#0f172a;margin:0">
            {{ isset($plan) ? '✏️ Edit Plan' : '➕ Add Pricing Plan' }}
        </h2>
        <p style="font-size:13px;color:#64748b;margin:2px 0 0">Admin Panel / Pricing Plans</p>
    </div>
    <a href="{{ route('admin.pricing.index') }}" class="btn btn-ghost btn-sm">← Back</a>
</div>

@if($errors->any())
<div style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:12px 16px;border-radius:10px;margin-bottom:16px;font-size:13px">
    @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
</div>
@endif

<form method="POST" action="{{ isset($plan) ? route('admin.pricing.update', $plan) : route('admin.pricing.store') }}">
    @csrf @if(isset($plan)) @method('PUT') @endif

    <div class="sg">

        {{-- Plan basics --}}
        <div class="card">
            <div class="card-head"><div class="card-title">📋 Plan Details</div></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Plan Name <span style="color:#ef4444">*</span></label>
                    <input class="form-control" type="text" name="name"
                           value="{{ old('name', $plan->name ?? '') }}"
                           required placeholder="Starter, Pro, Enterprise">
                </div>
                <div class="form-group">
                    <label class="form-label">Badge Label</label>
                    <input class="form-control" type="text" name="badge"
                           value="{{ old('badge', $plan->badge ?? '') }}"
                           placeholder="Most Popular, Best Value">
                    <div class="form-hint">Optional label shown above plan name</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Short Description</label>
                    <input class="form-control" type="text" name="description"
                           value="{{ old('description', $plan->description ?? '') }}"
                           placeholder="Perfect for individuals and small teams">
                </div>
            </div>
        </div>

        {{-- Pricing --}}
        <div class="card">
            <div class="card-head"><div class="card-title">💲 Pricing</div></div>
            <div class="card-body">
                <div class="g2col">
                    <div class="form-group">
                        <label class="form-label">Price</label>
                        <input class="form-control" type="number" name="price" step="0.01" min="0"
                               value="{{ old('price', $plan->price ?? '') }}"
                               placeholder="49">
                        <div class="form-hint">Leave blank for "Custom"</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Price Suffix</label>
                        <select class="form-control" name="price_suffix">
                            @foreach(['/mo', '/yr', '/month', '/year', 'one-time', ''] as $opt)
                            <option value="{{ $opt }}" {{ old('price_suffix', $plan->price_suffix ?? '/mo') === $opt ? 'selected' : '' }}>
                                {{ $opt ?: '(none)' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="g2col">
                    <div class="form-group">
                        <label class="form-label">CTA Button Label</label>
                        <input class="form-control" type="text" name="cta_label"
                               value="{{ old('cta_label', $plan->cta_label ?? 'Get Started') }}"
                               placeholder="Get Started">
                    </div>
                    <div class="form-group">
                        <label class="form-label">CTA Button URL</label>
                        <input class="form-control" type="text" name="cta_url"
                               value="{{ old('cta_url', $plan->cta_url ?? '') }}"
                               placeholder="/contact or https://...">
                    </div>
                </div>
            </div>
        </div>

        {{-- Features --}}
        <div class="card full">
            <div class="card-head"><div class="card-title">✅ Included Features</div></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Features</label>
                    <textarea class="form-control" name="features_raw" rows="10"
                              placeholder="One feature per line:&#10;Unlimited projects&#10;Priority support&#10;Custom domain&#10;Analytics dashboard">{{ old('features_raw', implode("\n", $plan->features ?? [])) }}</textarea>
                    <div class="form-hint">One feature per line — each will show with a ✓ checkmark</div>
                </div>
            </div>
        </div>

        {{-- Excluded features --}}
        <div class="card full">
            <div class="card-head"><div class="card-title">❌ Excluded Features <span style="font-size:12px;font-weight:400;color:#94a3b8">(shown crossed out)</span></div></div>
            <div class="card-body">
                <div class="form-group">
                    <textarea class="form-control" name="excluded_features_raw" rows="5"
                              placeholder="One feature per line:&#10;White-label branding&#10;Dedicated account manager">{{ old('excluded_features_raw', implode("\n", $plan->excluded_features ?? [])) }}</textarea>
                    <div class="form-hint">Optional — features the plan does NOT include</div>
                </div>
            </div>
        </div>

        {{-- Options --}}
        <div class="card">
            <div class="card-head"><div class="card-title">⚙️ Options</div></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Sort Order</label>
                    <input class="form-control" type="number" name="sort_order"
                           value="{{ old('sort_order', $plan->sort_order ?? 0) }}">
                </div>
                <label class="form-check" style="margin-bottom:10px">
                    <input type="checkbox" name="is_featured" value="1"
                           {{ old('is_featured', $plan->is_featured ?? false) ? 'checked' : '' }}>
                    <span>Featured plan (highlighted with purple border)</span>
                </label>
                <label class="form-check">
                    <input type="checkbox" name="is_published" value="1"
                           {{ old('is_published', $plan->is_published ?? true) ? 'checked' : '' }}>
                    <span>Published (visible on site)</span>
                </label>
            </div>
        </div>

        <div class="card">
            <div class="card-head"><div class="card-title">👁 Preview</div></div>
            <div class="card-body" id="plan-preview">
                <p style="font-size:13px;color:#94a3b8;text-align:center;padding:20px 0">
                    Fill in the form to see a preview
                </p>
            </div>
        </div>

    </div>

    <div style="margin-top:22px;display:flex;gap:10px">
        <button type="submit" class="btn btn-primary">
            💾 {{ isset($plan) ? 'Update Plan' : 'Create Plan' }}
        </button>
        <a href="{{ route('admin.pricing.index') }}" class="btn btn-ghost">Cancel</a>
    </div>
</form>

<script>
// Live preview update
function updatePreview(){
    var name  = document.querySelector('[name=name]').value || 'Plan Name';
    var price = document.querySelector('[name=price]').value;
    var suf   = document.querySelector('[name=price_suffix]').value;
    var desc  = document.querySelector('[name=description]').value;
    var feats = document.querySelector('[name=features_raw]').value.split('\n').filter(f=>f.trim());
    var badge = document.querySelector('[name=badge]').value;
    var feat  = document.querySelector('[name=is_featured]').checked;

    var featHtml = feats.slice(0,5).map(f=>`
        <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:#334155;margin-bottom:4px">
            <span style="color:#22c55e">✓</span> ${f.trim()}
        </div>`).join('');
    if(feats.length>5) featHtml += `<div style="font-size:12px;color:#94a3b8">+${feats.length-5} more</div>`;

    document.getElementById('plan-preview').innerHTML = `
        <div style="${feat?'border:2px solid #7c3aed;border-radius:10px;padding:16px':''}">
            ${badge?`<span style="display:inline-block;padding:2px 8px;background:#f3f0ff;color:#7c3aed;border-radius:20px;font-size:11px;font-weight:700;margin-bottom:8px">${badge}</span>`:''}
            <div style="font-size:16px;font-weight:800;color:#0f172a;margin-bottom:6px">${name}</div>
            <div style="font-size:26px;font-weight:900;color:#0f172a;margin-bottom:6px">
                ${price?`$${price}<span style="font-size:13px;font-weight:400;color:#64748b">${suf}</span>`:'<span style="font-size:18px">Custom</span>'}
            </div>
            ${desc?`<p style="font-size:13px;color:#64748b;margin:0 0 12px">${desc}</p>`:''}
            <div style="border-top:1px solid #f1f5f9;padding-top:10px">${featHtml||'<span style="font-size:12px;color:#94a3b8">No features yet</span>'}</div>
        </div>`;
}
document.querySelectorAll('input,textarea,select').forEach(el=>el.addEventListener('input',updatePreview));
updatePreview();
</script>
@endsection