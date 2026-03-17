@extends('admin.layouts.app')
@section('title', isset($career) ? 'Edit Job' : 'Post Job')
@section('content')
<style>
.main .content { box-sizing:border-box; width:100%; overflow-x:hidden; }
.sg   { display:grid; grid-template-columns:1fr 1fr; gap:22px; }
.sg .full { grid-column:1/-1; }
.g2col { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.g3col { display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; }
@media(max-width:900px){ .sg,.g2col,.g3col { grid-template-columns:1fr; } }
</style>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px">
    <div>
        <h2 style="font-size:20px;font-weight:800;color:#0f172a;margin:0">
            {{ isset($career) ? '✏️ Edit Job Posting' : '➕ Post New Job' }}
        </h2>
        <p style="font-size:13px;color:#64748b;margin:2px 0 0">Admin Panel / Careers</p>
    </div>
    <a href="{{ route('admin.careers.index') }}" class="btn btn-ghost btn-sm">← Back</a>
</div>

@if($errors->any())
<div style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:12px 16px;border-radius:10px;margin-bottom:16px;font-size:13px">
    @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
</div>
@endif

<form method="POST" action="{{ isset($career) ? route('admin.careers.update', $career) : route('admin.careers.store') }}">
    @csrf @if(isset($career)) @method('PUT') @endif

    <div class="sg">

        {{-- Job Basics --}}
        <div class="card full">
            <div class="card-head"><div class="card-title">💼 Job Details</div></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Job Title <span style="color:#ef4444">*</span></label>
                    <input class="form-control" type="text" name="title"
                           value="{{ old('title', $career->title ?? '') }}"
                           required placeholder="Senior Frontend Developer">
                </div>
                <div class="g3col">
                    <div class="form-group">
                        <label class="form-label">Department</label>
                        <input class="form-control" type="text" name="department"
                               value="{{ old('department', $career->department ?? '') }}"
                               placeholder="Engineering, Design, Marketing">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Location</label>
                        <input class="form-control" type="text" name="location"
                               value="{{ old('location', $career->location ?? '') }}"
                               placeholder="Remote, Karachi, Hybrid">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Experience</label>
                        <input class="form-control" type="text" name="experience"
                               value="{{ old('experience', $career->experience ?? '') }}"
                               placeholder="2-4 years">
                    </div>
                </div>
                <div class="g2col">
                    <div class="form-group">
                        <label class="form-label">Employment Type</label>
                        <select class="form-control" name="type">
                            @foreach(['Full-time','Part-time','Contract','Freelance','Internship'] as $t)
                            <option value="{{ $t }}" {{ old('type', $career->type ?? 'Full-time') === $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Application Deadline</label>
                        <input class="form-control" type="date" name="deadline"
                               value="{{ old('deadline', isset($career->deadline) ? $career->deadline->format('Y-m-d') : '') }}">
                        <div class="form-hint">Leave blank for open-ended</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Summary <span style="color:#ef4444">*</span></label>
                    <textarea class="form-control" name="summary" rows="3"
                              required placeholder="Short description shown on the careers listing page...">{{ old('summary', $career->summary ?? '') }}</textarea>
                    <div class="form-hint">2-3 sentences shown on the job card</div>
                </div>
            </div>
        </div>

        {{-- Full description --}}
        <div class="card full">
            <div class="card-head"><div class="card-title">📄 Full Job Description</div></div>
            <div class="card-body">
                <div class="form-group">
                    <textarea class="form-control" name="description" rows="8"
                              placeholder="Detailed role overview, company culture, team info...">{{ old('description', $career->description ?? '') }}</textarea>
                    <div class="form-hint">Shown on the individual job detail page</div>
                </div>
            </div>
        </div>

        {{-- Responsibilities --}}
        <div class="card">
            <div class="card-head"><div class="card-title">📌 Responsibilities</div></div>
            <div class="card-body">
                <div class="form-group">
                    <textarea class="form-control" name="responsibilities_raw" rows="8"
                              placeholder="One item per line:&#10;Build and maintain frontend components&#10;Collaborate with design team&#10;Write unit and integration tests&#10;Participate in code reviews">{{ old('responsibilities_raw', implode("\n", $career->responsibilities ?? [])) }}</textarea>
                    <div class="form-hint">One responsibility per line</div>
                </div>
            </div>
        </div>

        {{-- Requirements --}}
        <div class="card">
            <div class="card-head"><div class="card-title">✅ Requirements</div></div>
            <div class="card-body">
                <div class="form-group">
                    <textarea class="form-control" name="requirements_raw" rows="8"
                              placeholder="One item per line:&#10;3+ years React experience&#10;Strong CSS/Tailwind skills&#10;Experience with Git&#10;Good communication in English">{{ old('requirements_raw', implode("\n", $career->requirements ?? [])) }}</textarea>
                    <div class="form-hint">One requirement per line</div>
                </div>
            </div>
        </div>

        {{-- Benefits --}}
        <div class="card">
            <div class="card-head"><div class="card-title">🎁 Benefits</div></div>
            <div class="card-body">
                <div class="form-group">
                    <textarea class="form-control" name="benefits_raw" rows="6"
                              placeholder="One item per line:&#10;Competitive salary&#10;Flexible remote work&#10;Health insurance&#10;Learning & development budget">{{ old('benefits_raw', implode("\n", $career->benefits ?? [])) }}</textarea>
                    <div class="form-hint">One benefit per line</div>
                </div>
            </div>
        </div>

        {{-- Apply --}}
        <div class="card">
            <div class="card-head"><div class="card-title">📬 How to Apply</div></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Apply Email</label>
                    <input class="form-control" type="email" name="apply_email"
                           value="{{ old('apply_email', $career->apply_email ?? '') }}"
                           placeholder="careers@skartistic.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Apply URL</label>
                    <input class="form-control" type="url" name="apply_url"
                           value="{{ old('apply_url', $career->apply_url ?? '') }}"
                           placeholder="https://forms.google.com/...">
                    <div class="form-hint">If set, CTA button links here instead of email</div>
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
                           value="{{ old('sort_order', $career->sort_order ?? 0) }}">
                </div>
                <label class="form-check" style="margin-top:8px">
                    <input type="checkbox" name="is_published" value="1"
                           {{ old('is_published', $career->is_published ?? true) ? 'checked' : '' }}>
                    <span>Published (visible on site)</span>
                </label>
            </div>
        </div>

    </div>

    <div style="margin-top:22px;display:flex;gap:10px">
        <button type="submit" class="btn btn-primary">
            💾 {{ isset($career) ? 'Update Job Posting' : 'Post Job' }}
        </button>
        <a href="{{ route('admin.careers.index') }}" class="btn btn-ghost">Cancel</a>
    </div>
</form>
@endsection