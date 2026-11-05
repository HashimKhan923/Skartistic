@extends('admin.layouts.app')
@section('title', isset($member) ? 'Edit Member' : 'Add Member')

@section('content')
<div class="dash-grid">
    <form method="POST" id="teamForm" action="{{ isset($member) ? route('admin.team.update', $member) : route('admin.team.store') }}" enctype="multipart/form-data">
        @csrf @if(isset($member)) @method('PUT') @endif

        <div class="card">
            <div class="card-head">
                <div class="card-title">{{ isset($member) ? 'Edit Member' : 'Add Team Member' }}</div>
                <div style="display:flex;gap:8px">
                    @if(isset($member) && $member->slug && $member->is_published)
                    <a href="{{ route('team.member', $member->slug) }}" target="_blank" rel="noopener" class="btn btn-ghost btn-sm">View Profile</a>
                    @endif
                    <a href="{{ route('admin.team.index') }}" class="btn btn-ghost btn-sm">← Back</a>
                </div>
            </div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="m-name">Full Name <span class="req">*</span></label>
                        <input id="m-name" class="form-control" type="text" name="name" value="{{ old('name', $member->name ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="m-position">Position / Role <span class="req">*</span></label>
                        <input id="m-position" class="form-control" type="text" name="position" value="{{ old('position', $member->position ?? '') }}" required placeholder="UI/UX Designer">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="m-bio">Bio</label>
                    <textarea id="m-bio" class="form-control" name="bio" rows="6" placeholder="Tell their story. The team page shows the start of this; the profile page shows all of it.">{{ old('bio', $member->bio ?? '') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="m-expertise">Expertise</label>
                    <textarea id="m-expertise" class="form-control" name="expertise_raw" rows="4" placeholder="One per line&#10;Product Strategy&#10;AI Solutions">{{ old('expertise_raw', implode("\n", $member->expertise ?? [])) }}</textarea>
                    <div class="form-hint">One per line. Each line becomes a tag.</div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="m-education">Education</label>
                    <textarea id="m-education" class="form-control" name="education_raw" rows="4" placeholder="One per line&#10;BS Computer Science, University Name, 2018">{{ old('education_raw', implode("\n", $member->education ?? [])) }}</textarea>
                    <div class="form-hint">One qualification per line.</div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="m-achievements">Certifications &amp; Achievements</label>
                    <textarea id="m-achievements" class="form-control" name="achievements_raw" rows="4" placeholder="One per line&#10;Certified Project Manager, 2024">{{ old('achievements_raw', implode("\n", $member->achievements ?? [])) }}</textarea>
                    <div class="form-hint">One per line.</div>
                </div>
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label" for="m-sort">Sort Order</label>
                    <input id="m-sort" class="form-control" type="number" name="sort_order" value="{{ old('sort_order', $member->sort_order ?? 0) }}" style="max-width:120px">
                </div>
            </div>
        </div>

        <div class="card" style="margin-top:16px">
            <div class="card-head"><div class="card-title">Success Gallery</div></div>
            <div class="card-body">
                @if(!empty($member->gallery))
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:12px;margin-bottom:16px">
                    @foreach($member->gallery as $image)
                    <label style="display:block;cursor:pointer">
                        <img src="{{ asset('storage/'.$image) }}" alt="" style="width:100%;aspect-ratio:4/3;object-fit:contain;background:var(--a-soft);border:1px solid var(--a-line);border-radius:10px">
                        <span class="form-hint" style="display:flex;align-items:center;gap:6px;margin-top:6px"><input type="checkbox" name="remove_gallery[]" value="{{ $image }}"> Remove</span>
                    </label>
                    @endforeach
                </div>
                @endif
                <input type="file" name="gallery[]" accept="image/*" multiple class="form-control">
                <div class="form-hint">Add photos of awards, events and milestones. You can select several at once.</div>
            </div>
        </div>

        <div style="margin-top:16px;display:flex;gap:10px">
            <button type="submit" class="btn btn-primary">{{ isset($member) ? 'Update Member' : 'Add Member' }}</button>
            <a href="{{ route('admin.team.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>

    <div style="position:sticky;top:92px;display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-head"><div class="card-title">Photo</div></div>
            <div class="card-body">
                @if(isset($member) && $member->photo)
                <img src="{{ asset('storage/'.$member->photo) }}" alt="" style="width:100%;max-height:260px;object-fit:contain;background:var(--a-soft);border-radius:12px;margin-bottom:12px">
                @endif
                <input type="file" name="photo" accept="image/*" class="form-control" form="teamForm">
            </div>
        </div>
        <div class="card">
            <div class="card-head"><div class="card-title">Where to Find Them</div></div>
            <div class="card-body">
                @foreach(['linkedin'=>'LinkedIn','instagram'=>'Instagram','twitter'=>'X / Twitter','facebook'=>'Facebook','github'=>'GitHub','youtube'=>'YouTube','whatsapp'=>'WhatsApp link','website'=>'Website'] as $key => $label)
                <div class="form-group" style="margin-bottom:14px">
                    <label class="form-label" for="m-{{ $key }}">{{ $label }}</label>
                    <input id="m-{{ $key }}" class="form-control" type="url" name="{{ $key }}" value="{{ old($key, $member->$key ?? '') }}" placeholder="https://" form="teamForm">
                </div>
                @endforeach
                <label class="form-check">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $member->is_published ?? true) ? 'checked' : '' }} form="teamForm">
                    <span>Show on website</span>
                </label>
            </div>
        </div>
    </div>
</div>
@endsection
